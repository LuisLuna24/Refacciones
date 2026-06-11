<?php

namespace App\Livewire\Admin\Inventories\Viniles;

use App\Models\VinilType;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class Forms extends Component
{
    // Permitimos que sea null para el modo de creación
    public ?VinilType $vinilType = null;

    public $name = '';
    public $pressure = 0;
    public $prices = [];
    public $typeForm = 1; // 1 para crear, 2 para editar
    public $id;

    public function mount()
    {
        // Verificamos que exista y tenga un ID válido
        if ($this->vinilType && $this->vinilType->exists) {
            $this->id = $this->vinilType->id;
            $this->name = $this->vinilType->name;
            $this->pressure = $this->vinilType->pressure;
            
            // Mapeamos los precios existentes a la estructura que espera Alpine
            $this->prices = $this->vinilType->prices->map(function ($price) {
                return [
                    'id' => $price->id,
                    'description' => $price->name,
                    'price' => $price->price,
                ];
            })->toArray();
            
            $this->typeForm = 2;
        }
    }

    public function saveVinil()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'pressure' => 'required|numeric|min:0',
            'prices' => 'array',
            'prices.*.description' => 'required|string',
            'prices.*.price' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            // 1. Guardar el Vinil Principal
            $vinil = VinilType::updateOrCreate(
                ['id' => $this->id], 
                [
                    'name' => $this->name,
                    'pressure' => $this->pressure,
                ]
            );

            // 2. Guardar Precios y recolectar los IDs que se mantienen/crean
            $savedPriceIds = [];
            foreach ($this->prices as $price) {
                $precioRecord = $vinil->prices()->updateOrCreate(
                    ['id' => $price['id'] ?? null],
                    [
                        'name' => $price['description'], 
                        'price' => $price['price']
                    ]
                );
                $savedPriceIds[] = $precioRecord->id;
            }

            // 3. Eliminar los precios que el usuario quitó en la interfaz
            $vinil->prices()->whereNotIn('id', $savedPriceIds)->delete();

            DB::commit();

            if ($this->typeForm == 2) {
                $text = 'Actualizado correctamente';
            } else {
                $text = 'Creado correctamente';
                $this->reset(['name', 'pressure', 'prices']);
            }

            $this->dispatch('swal', ['icon' => 'success', 'title' => 'Éxito', 'text' => $text]);
            
            return redirect()->route('admin.viniles.index');

        } catch (\Exception $e) {
            // El Rollback SIEMPRE debe ir primero
            DB::rollBack(); 
            
            // Guardamos el error en el log en lugar de romper la pantalla con dd()
            Log::error("Error guardando vinil: " . $e->getMessage());
            
            $this->dispatch('swal', ['icon' => 'error', 'title' => 'Error', 'text' => 'Lo sentimos, ha ocurrido un error inesperado.']);
        }
    }

    public function render()
    {
        return view('livewire.admin.inventories.viniles.forms');
    }
}
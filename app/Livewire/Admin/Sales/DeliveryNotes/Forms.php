<?php

namespace App\Livewire\Admin\Sales\DeliveryNotes;

use App\Models\Customer;
use App\Models\DeliveryNote;
use App\Models\Product;
use App\Models\Warehouse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Forms extends Component
{
    public DeliveryNote $deliveryNote;

    public $id;
    public $typeForm = 1;

    public $customer_id = null;
    public $warehouse_id = '';
    public $voucher_type = '1';
    public $serie = '';
    public $correlative = '';
    public $date;
    public $observation = '';
    public $installment = 0;
    public $status = 1;

    public $guest_name;
    public $guest_phone;
    public $guest_email;


    public $items = [];

    public function mount()
    {
        $this->date = now()->format('Y-m-d');
        $this->serie = 'NOTE' . now()->format('Y');
        $this->correlative = DeliveryNote::max('id') + 1;
        $this->addItem();

        if (isset($this->deliveryNote)) {
            $this->id = $this->deliveryNote->id;
            $this->customer_id = $this->deliveryNote->customer_id;
            $this->guest_name = $this->deliveryNote->guest_name;
            $this->guest_phone = $this->deliveryNote->guest_phone;
            $this->guest_email = $this->deliveryNote->guest_email;
            $this->warehouse_id = $this->deliveryNote->warehouse_id ?? Auth::user()->warehouse_id;;
            $this->voucher_type = $this->deliveryNote->voucher_type;
            $this->serie = $this->deliveryNote->serie;
            $this->correlative = $this->deliveryNote->correlative;

            $this->date = $this->deliveryNote->date ? $this->deliveryNote->date->format('Y-m-d') : now()->format('Y-m-d');

            $this->installment = $this->deliveryNote->installment;
            $this->observation = $this->deliveryNote->observation;
            $this->status = $this->deliveryNote->status;

            $this->typeForm = 2;

            $this->items = [];

            foreach ($this->deliveryNote->items as $item) {
                $this->items[] = [
                    'product_id' => $item->product_id,
                    'description' => $item->description,
                    'quantity' => $item->quantity,
                    'price' => $item->price,
                ];
            }
        } else {
            $this->warehouse_id = Auth::user()->warehouse_id;
        }
    }

    public function updated($property, $value)
    {
        // Verificamos si la propiedad que cambió es un product_id
        if (str_starts_with($property, 'items.') && str_ends_with($property, '.product_id')) {

            $parts = explode('.', $property);
            $index = (int) $parts[1]; // El índice de la fila actual

            if ($value) {
                // 1. Verificar si el producto ya está en OTRA fila
                $existingIndex = null;

                foreach ($this->items as $i => $item) {
                    // Si encontramos el mismo product_id y NO es la fila en la que estamos editando
                    if ($i !== $index && isset($item['product_id']) && $item['product_id'] == $value) {
                        $existingIndex = $i;
                        break;
                    }
                }

                // 2. Si ya existe, sumamos la cantidad y reseteamos la fila actual
                if ($existingIndex !== null) {
                    // Tomamos la cantidad que ya tenía la fila existente (por defecto 0 si estaba vacía)
                    $currentQty = (float) ($this->items[$existingIndex]['quantity'] ?? 0);

                    // Tomamos la cantidad de la fila actual (usualmente 1 si se acaba de agregar)
                    $addedQty = (float) ($this->items[$index]['quantity'] ?? 1);

                    // Sumamos
                    $this->items[$existingIndex]['quantity'] = $currentQty + $addedQty;

                    // Limpiamos la fila actual para que el usuario pueda buscar otro producto en ella
                    $this->items[$index]['product_id'] = null;
                    $this->items[$index]['description'] = '';
                    $this->items[$index]['price'] = 0;
                    $this->items[$index]['quantity'] = 1; // Devolvemos la cantidad a 1 por defecto

                    return; // Salimos de la función para no consultar la BD
                }

                // 3. Si NO existe, consultamos la BD como lo hacíamos antes
                $product = Product::find($value);

                if ($product) {
                    $this->items[$index]['price'] = $product->price;

                    if (empty($this->items[$index]['description'])) {
                        $this->items[$index]['description'] = $product->name;
                    }
                }
            } else {
                // Si el usuario vacía el select, limpiamos los datos de esa fila
                $this->items[$index]['price'] = 0;
                $this->items[$index]['description'] = '';
            }
        }
    }


    // Método para agregar una nueva fila vacía
    public function addItem()
    {
        $this->items[] = [
            'product_id' => null,
            'description' => '',
            'quantity' => 1,
            'price' => 0,
        ];
    }

    // Método para eliminar una fila específica
    public function removeItem($index)
    {
        unset($this->items[$index]);
        $this->items = array_values($this->items); // Reindexar el arreglo
    }

    public function getTotal()
    {
        return collect($this->items)->sum(function ($item) {
            return (float)$item['quantity'] * (float)$item['price'];
        });
    }

    // Método para calcular lo que falta por pagar
    public function getBalance()
    {
        return $this->getTotal() - (float)$this->installment;
    }

    public function statusDelivery()
    {
        if ($this->installment < $this->getTotal()) {
            $this->status = 1; // Pendiente de pago
        } else {
            $this->status = 2; // Total pagoado
        }
    }

    public function save()
    {
        $this->validate([
            'customer_id' => ['nullable', 'exists:customers,id'],
            'guest_name' => ['nullable', 'string', 'max:100'],
            'guest_phone' => ['nullable', 'string', 'max:20'],
            'guest_email' => ['nullable', 'string', 'max:100'],
            'warehouse_id' => ['required', 'exists:warehouses,id'],
            'voucher_type' => ['required', 'in:1,2'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.description' => [
                'required_without:items.*.product_id',
                'nullable',
                'string'
            ],
            'items.*.quantity' => ['required', 'numeric', 'min:0.1'],
            'items.*.price' => ['required', 'numeric', 'min:0'],
            'observation' => ['nullable', 'string', 'max:255'],
        ], [], [
            'customer_id' => 'cliente',
            'warehouse_id' => 'almacén',
            'items' => 'productos',
            'items.*.description' => 'descripción del producto',
        ]);

        $this->statusDelivery();

        DB::beginTransaction();
        try {
            // Guardar o actualizar la cabecera
            $note = DeliveryNote::updateOrCreate(
                ['id' => $this->id ?? null],
                [
                    'customer_id' => $this->customer_id ?: null,
                    'guest_name' => $this->guest_name ?: null,
                    'guest_phone' => $this->guest_phone ?: null,
                    'guest_email' => $this->guest_email ?: null,
                    'warehouse_id' => $this->warehouse_id,
                    'voucher_type' => $this->voucher_type,
                    'serie' => $this->serie,
                    'correlative' => $this->correlative,
                    'date' => $this->date,
                    'total' => $this->getTotal(),
                    'installment' => $this->installment,
                    'observation' => $this->observation,
                    'status' => $this->status,
                ]
            );
            if (isset($this->id)) {
                $note->items()->delete();
            }
            foreach ($this->items as $item) {
                $note->items()->create([
                    'product_id' => !empty($item['product_id']) ? $item['product_id'] : null,
                    'description' => $item['description'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'subtotal' => $item['quantity'] * $item['price'],
                ]);
            }

            DB::commit();

            if ($this->typeForm == 2) {
                $text = 'Actualizado correctamente';
            } else {
                $text = 'Creado correctamente';
                $this->reset([
                    'customer_id',
                    'guest_name',
                    'guest_phone',
                    'guest_email',
                    'warehouse_id',
                    'serie',
                    'correlative',
                    'observation',
                    'installment'
                ]);
                $this->items = [];
                $this->addItem();
            }

            $this->dispatch('swal', [
                'icon' => 'success',
                'title' => 'Éxito',
                'text' => $text
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            //dd($e->getMessage());
            $this->dispatch('swal', [
                'icon' => 'error',
                'title' => 'Error',
                'text' => 'Lo sentimos ha ocurrido un error inesperado.'
            ]);
        }
    }
    public function render()
    {
        return view('livewire.admin.sales.delivery-notes.forms', [
            'customers' => Customer::all(),
            'warehouses' => Warehouse::all(),
            'catalogProducts' => Product::all(), // Para el select opcional
        ]);
    }
}

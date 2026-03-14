<?php

namespace App\Livewire\Admin\Inventories\Products;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Forms extends Component
{

    public Product $product;

    public $typeForm = 1;

    public $categories = [];

    public $id, $name, $description, $barcode, $sku, $price, $cost, $category_id, $supplier_id;
    public $units_package;
    public $cost_package;

    public $porcent = 0;

    public $iva = 16;

    // NUEVA PROPIEDAD: Controla si se aplica o no el IVA
    public bool $apply_iva = true;

    public function mount()
    {
        $this->categories = Category::all();

        if (isset($this->product)) {
            $this->id = $this->product->id;
            $this->name = $this->product->name;
            $this->description = $this->product->description;
            $this->barcode = $this->product->barcode;
            $this->sku = $this->product->sku;
            $this->price = $this->product->price;
            $this->cost = $this->product->cost;
            $this->units_package = $this->product->units_package;
            $this->cost_package = $this->product->cost_package;
            $this->category_id = $this->product->category_id;
            $this->supplier_id = $this->product->supplier_id;
            $this->apply_iva = $this->product->apply_iva;

            $this->typeForm = 2;
        }
    }

    public function updated($propertyName)
    {
        // Agregamos 'apply_iva' para que recalcule al hacer clic en el checkbox
        if (in_array($propertyName, ['cost', 'category_id', 'apply_iva'])) {
            $this->calculatePrice();
        }

        if (in_array($propertyName, ['cost_package', 'units_package', 'category_id', 'apply_iva'])) {
            $this->calculatePricePakage();
        }
    }

    public function calculatePrice()
    {
        if ($this->cost > 0 && $this->category_id) {
            $category = Category::find($this->category_id);
            $this->porcent = $category ? $category->porcent : 0;

            // 1. Subtotal (Costo + Ganancia)
            $subtotal = floatval($this->cost) + (floatval($this->cost) * floatval($this->porcent) / 100);

            // 2. Aplicar IVA solo si el checkbox está activo
            if ($this->apply_iva) {
                $total_final = $subtotal * (1 + ($this->iva / 100));
            } else {
                $total_final = $subtotal;
            }

            // 3. Formato
            $this->price = number_format($total_final, 2, '.', '');
        } else {
            // Si no hay categoría pero sí costo y queremos aplicar IVA al costo base
            if ($this->apply_iva && $this->cost > 0) {
                $this->price = number_format(floatval($this->cost) * (1 + ($this->iva / 100)), 2, '.', '');
            } else {
                $this->price = $this->cost;
            }
        }
    }

    public function calculatePricePakage()
    {
        if ($this->cost_package > 0 && $this->units_package > 0) {
            $cost_total = $this->cost_package / $this->units_package;
            $this->cost = number_format($cost_total, 2, '.', '');
            $this->calculatePrice();
        }
    }

    public function save()
    {
        $this->validate([
            "name" => ['required', 'string', 'max:255', 'unique:products,name,' . $this->id . ',id'],
            "description" => ['nullable', 'string', 'max:500'],
            "barcode" => ['nullable', 'numeric'],
            "sku" => ['nullable', 'string', 'max:50'],
            "price" => ['nullable', 'numeric', 'min:1'],
            "cost" => ['nullable', 'numeric', 'min:1'],
            "units_package" => ['nullable', 'numeric', 'min:1'],
            "cost_package" => ['nullable', 'numeric', 'min:1'],
            "category_id" => ['required', 'exists:categories,id'],
            "supplier_id" => ['required', 'exists:suppliers,id'],
            "apply_iva" => ['boolean'], // Validamos el boolean
        ], [], ['category_id' => 'categoría', 'supplier_id' => 'proveedor']);

        DB::beginTransaction();
        try {

            Product::updateOrCreate(['id' => $this->id], [
                'name' => $this->name,
                'description' => $this->description,
                'barcode' => $this->barcode,
                'sku' => $this->sku,
                'price' => $this->price,
                'cost' => $this->cost,
                'units_package' => $this->units_package,
                'cost_package' => $this->cost_package,
                'porcent' => $this->porcent,
                'apply_iva' => $this->apply_iva, // Descomenta si lo agregas a tu BD
                'category_id' => $this->category_id,
                'supplier_id' => $this->supplier_id
            ]);

            if ($this->typeForm == 2) {
                $text = 'Actualizado correctamente';
            } else {
                $text = 'Creado correctamente';
                $this->reset([
                    'name',
                    'description',
                    'barcode',
                    'sku',
                    'price',
                    'cost',
                    'units_package',
                    'cost_package',
                    'porcent',
                    'category_id',
                    'supplier_id',
                    'apply_iva' // Reseteamos el checkbox al crear
                ]);
            }

            DB::commit();
            $this->dispatch('swal', ['icon' => 'success', 'title' => 'Éxito', 'text' => $text]);
        } catch (\Exception $e) {
            $this->dispatch('swal', ['icon' => 'error', 'title' => 'Error', 'text' => 'Lo sentimos ha ocurrido un error inesperado.']);
            DB::rollBack();
        }
    }

    public function render()
    {
        return view('livewire.admin.inventories.products.forms');
    }
}

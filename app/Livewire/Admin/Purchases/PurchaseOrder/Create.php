<?php

namespace App\Livewire\Admin\Purchases\PurchaseOrder;

use App\Models\Inventory;
use App\Models\Product;
use App\Models\PurchaseOrder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class Create extends Component
{
    use WithPagination;

    // Filtros
    public $search = '';

    // Datos del Formulario
    public $supplier_id;
    public $warehouse_id;
    public $voucher_type = 1;
    public $serie;
    public $correlative;
    public $date;
    public $total = 0.00;
    public $observation;
    public $category_id;


    // Productos
    public $product_id;
    public $products = [];

    public function mount()
    {
        $this->date = now()->format('Y-m-d');
        $this->serie = 'OC' . now()->format('Y');
        $this->correlative = PurchaseOrder::max('id') + 1;

        $this->warehouse_id = Auth::user()->warehouse_id;
    }

    // Agregar producto desde la tarjeta
    public function addFromCard($id)
    {
        // Validar que haya proveedor seleccionado antes de agregar
        if (!$this->supplier_id) {
            $this->dispatch('swal', [
                'icon' => 'warning',
                'title' => 'Proveedor Requerido',
                'text' => 'Por favor selecciona un proveedor primero.',
            ]);
            return;
        }

        $this->product_id = $id;
        $this->addProduct();
    }

    public function addProduct()
    {
        $this->validate([
            'product_id' => ['required', 'exists:products,id'],
            'warehouse_id' => ['required', 'exists:warehouses,id'],
            'supplier_id' => ['required', 'exists:suppliers,id']
        ], [], [
            'product_id' => 'producto',
            'warehouse_id' => 'almacén',
            'supplier_id' => 'proveedor'
        ]);

        $existing = collect($this->products)->firstWhere('id', $this->product_id);

        if ($existing) {
            $this->dispatch('swal', [
                'icon' => 'info',
                'title' => 'Ya agregado',
                'text' => 'El producto ya está en la orden.',
            ]);
            return;
        }

        $product = Product::find($this->product_id);

        // Lógica para determinar si usa paquetes y establecer precios
        $hasPackages = $product->cost_package > 0;
        $defaultType = $hasPackages ? 'package' : 'unit';
        $defaultPrice = $hasPackages ? $product->cost_package : $product->cost;

        $this->products[] = [
            'id' => $product->id,
            'name' => $product->name,
            'sku' => $product->sku ?? '',
            // Datos para AlpineJS
            'has_packages' => $hasPackages,
            'purchase_type' => $defaultType,
            'unit_price' => $product->cost,
            'package_price' => $product->cost_package,
            'units_per_package' => $product->units_package ?? 1,
            // Datos del carrito
            'price' => $defaultPrice,
            'quantity' => 1,
            'subtotal' => $defaultPrice,
        ];

        $this->reset(['product_id', 'search']);
    }

    public function save()
    {
        $this->validate([
            'voucher_type' => ['required', 'in:1,2'],
            'date' => ['nullable', 'date'],
            'supplier_id' => ['required', 'exists:suppliers,id'],
            'warehouse_id' => ['required', 'exists:warehouses,id'],
            'total' => ['required', 'numeric', 'min:0'],
            'observation' => ['nullable', 'string', 'max:255'],
            'products' => ['required', 'array', 'min:1'],
        ], [], ['supplier_id' => 'proveedor', 'products' => 'productos']);

        DB::beginTransaction();

        try {
            $purchaseOrder = PurchaseOrder::create([
                'voucher_type' => $this->voucher_type,
                'serie' => $this->serie,
                'correlative' => $this->correlative,
                'date' => $this->date ?? now(),
                'supplier_id' => $this->supplier_id,
                'warehouse_id' => $this->warehouse_id,
                'total' => $this->total,
                'observation' => $this->observation,
                'status' => 'pending'
            ]);

            foreach ($this->products as $product) {
                // Verificamos si el usuario seleccionó el toggle de "paquete"
                $isPackage = $product['purchase_type'] === 'package';

                // Calculamos la CANTIDAD TOTAL física (Para el Kardex/Stock)
                $totalPhysicalQuantity = $isPackage
                    ? ($product['quantity'] * $product['units_per_package']) // Ej: 2 paquetes * 12 uds = 24
                    : $product['quantity']; // Ej: 5 unidades sueltas = 5

                // Guardamos en la tabla pivote productable respetando tus columnas
                $purchaseOrder->products()->attach($product['id'], [
                    'quantity' => $totalPhysicalQuantity, // Total de piezas sueltas (Ej: 24)
                    'price' => $product['price'],
                    'subtotal' => $product['quantity'] * $product['price'],
                    'ck_pakage' => $isPackage ? 1 : 0,
                    'quantity_pacage' => $isPackage ? $product['quantity'] : null, // La cantidad de paquetes (Ej: 2)
                ]);
            }

            DB::commit();

            session()->flash('swal', [
                'icon' => 'success',
                'title' => '¡Orden Generada!',
                'text' => 'La orden de compra se ha creado correctamente.',
            ]);

            return redirect()->route('admin.purchase_orders.index');
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('swal', [
                'icon' => 'error',
                'title' => 'Error',
                'text' => 'Ocurrió un error: ' . $e->getMessage(),
            ]);
        }
    }

    public function render()
    {
        $warehouseId = $this->warehouse_id;
        $supplierId = $this->supplier_id;

        $catalog = Product::query()
            ->select('products.*')

            ->where(function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('sku', 'like', '%' . $this->search . '%')
                    ->orWhere('barcode', 'like', '%' . $this->search . '%')
                    ->orWhereHas('tags', function ($tagQuery) {
                        $tagQuery->where('name', 'like', '%' . $this->search . '%');
                    });
            })

            ->when($this->category_id, function ($query) {
                $query->where('category_id', $this->category_id);
            })

            ->when($warehouseId, function ($query) use ($warehouseId) {
                $query->addSelect([
                    'stock' => Inventory::select('quantity_balance')
                        ->whereColumn('product_id', 'products.id')
                        ->where('warehouse_id', $warehouseId)
                        ->orderBy('id', 'desc')
                        ->limit(1)
                ]);
                $query->orderBy('stock', 'desc');
            })

            ->with(['category', 'tags'])
            ->paginate(16, pageName: 'products-page');

        return view('livewire.admin.purchases.purchase-order.create', compact('catalog'));
    }
}

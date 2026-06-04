<?php

namespace App\Livewire\Admin\Purchases\Purchases;

use App\Facades\Kardex;
use App\Models\Inventory;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\PurchaseOrder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class PurchaseCreate extends Component
{
    use WithPagination;

    // Búsqueda y Filtros
    public $search = '';

    // Datos del Formulario
    public $purchase_order_id;
    public $supplier_id;
    public $warehouse_id;
    public $voucher_type = 1; // 1: Factura por defecto en compras
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
        $this->serie = 'COM' . now()->format('Y');
        $this->correlative = (Purchase::max('correlative') ?? 0) + 1;
    }

    // Detectar cambios (ej. cargar Orden de Compra)
    public function updated($property, $value)
    {
        if ($property === 'purchase_order_id') {
            $purchaseOrder = PurchaseOrder::with('products')->find($value);

            if ($purchaseOrder) {
                $this->voucher_type = $purchaseOrder->voucher_type;
                $this->supplier_id = $purchaseOrder->supplier_id;
                $this->warehouse_id = $purchaseOrder->warehouse_id ?? Auth::user()->warehouse_id;;

                // Mapeamos los productos de la orden adaptándolos a la nueva lógica de Paquetes
                $this->products = $purchaseOrder->products->map(function ($product) {
                    $isPackage = (bool) ($product->pivot->ck_pakage ?? false);
                    $hasPackages = $product->cost_package > 0;

                    // Si se compró por paquete en la OC, mostramos la cantidad de paquetes
                    $uiQuantity = $isPackage ? $product->pivot->quantity_pacage : $product->pivot->quantity;

                    return [
                        'id' => $product->id,
                        'name' => $product->name,
                        'sku' => $product->sku ?? '',
                        'has_packages' => $hasPackages,
                        'purchase_type' => $isPackage ? 'package' : 'unit',
                        'unit_price' => (float) $product->cost,
                        'package_price' => (float) $product->cost_package,
                        'units_per_package' => (int) ($product->units_package ?? 1),
                        'price' => (float) $product->pivot->price, // Costo pactado en la OC
                        'quantity' => (float) $uiQuantity,
                        'subtotal' => (float) $product->pivot->subtotal,
                    ];
                })->toArray();
            }
        } else {
            $this->warehouse_id = Auth::user()->warehouse_id;
        }
    }

    public function addFromCard($id)
    {
        $this->product_id = $id;
        $this->addProduct();
    }

    public function addProduct()
    {
        $this->validate([
            'product_id' => ['required', 'exists:products,id'],
            'warehouse_id' => ['required', 'exists:warehouses,id']
        ], [], ['product_id' => 'producto', 'warehouse_id' => 'almacén']);

        $existing = collect($this->products)->firstWhere('id', $this->product_id);

        if ($existing) {
            $this->dispatch('swal', [
                'icon' => 'info',
                'title' => 'Producto en lista',
                'text' => 'Este producto ya está agregado. Ajusta la cantidad en la lista.',
            ]);
            return;
        }

        $product = Product::find($this->product_id);

        // Lógica de paquetes al agregar manualmente
        $hasPackages = $product->cost_package > 0;
        $defaultType = $hasPackages ? 'package' : 'unit';
        $defaultPrice = $hasPackages ? $product->cost_package : $product->cost;

        $this->products[] = [
            'id' => $product->id,
            'name' => $product->name,
            'sku' => $product->sku ?? '',
            'has_packages' => $hasPackages,
            'purchase_type' => $defaultType,
            'unit_price' => (float) $product->cost,
            'package_price' => (float) $product->cost_package,
            'units_per_package' => (int) ($product->units_package ?? 1),
            'price' => (float) $defaultPrice,
            'quantity' => 1,
            'subtotal' => (float) $defaultPrice,
        ];

        $this->reset(['product_id', 'search']);
    }

    public function removeProduct($index)
    {
        unset($this->products[$index]);
        $this->products = array_values($this->products);
    }

    public function save()
    {
        $this->validate([
            'voucher_type' => ['required', 'in:1,2'],
            'date' => ['nullable', 'date'],
            'supplier_id' => ['required', 'exists:suppliers,id'],
            'purchase_order_id' => ['nullable', 'exists:purchase_orders,id'],
            'warehouse_id' => ['required', 'exists:warehouses,id'],
            'total' => ['required', 'numeric', 'min:0'],
            'observation' => ['nullable', 'string', 'max:255'],
            'products' => ['required', 'array', 'min:1'],
            'products.*.id' => ['required', 'exists:products,id'],
            'products.*.quantity' => ['required', 'numeric', 'min:0.1'],
            'products.*.price' => ['required', 'numeric', 'min:0'],
        ], [], ['supplier_id' => 'proveedor', 'products' => 'productos']);

        DB::beginTransaction();

        try {
            $purchase = Purchase::create([
                'voucher_type' => $this->voucher_type,
                'serie' => $this->serie,
                'correlative' => $this->correlative,
                'date' => $this->date ?? now(),
                'supplier_id' => $this->supplier_id,
                'purchase_order_id' => $this->purchase_order_id,
                'warehouse_id' => $this->warehouse_id,
                'total' => $this->total,
                'observation' => $this->observation,
            ]);

            foreach ($this->products as $product) {
                // Evaluamos si es paquete
                $isPackage = $product['purchase_type'] === 'package';

                // Calculamos LA CANTIDAD FÍSICA REAL que entrará al inventario
                $totalPhysicalQuantity = $isPackage
                    ? ($product['quantity'] * $product['units_per_package'])
                    : $product['quantity'];

                $subtotal = $product['quantity'] * $product['price'];

                // ¡Nota importante! Asegúrate de que la tabla pivote de Purchase
                // también tenga las columnas ck_pakage y quantity_pacage
                $purchase->products()->attach($product['id'], [
                    'quantity' => $totalPhysicalQuantity,
                    'price' => $product['price'],
                    'subtotal' => $subtotal,
                    'ck_pakage' => $isPackage ? 1 : 0,
                    'quantity_pacage' => $isPackage ? $product['quantity'] : null,
                ]);

                // ¡SÚPER CRÍTICO PARA EL KARDEX!
                // Clonamos el array del producto para inyectarle la cantidad física total
                // Si no hacemos esto, el Kardex registraría '2' en vez de '24' piezas
                $productForKardex = $product;
                $productForKardex['quantity'] = $totalPhysicalQuantity;

                // Registro de Entrada en Kardex con la cantidad física
                Kardex::registerEntry($purchase->id, Purchase::class, $productForKardex, $this->warehouse_id, 'Compra');
            }

            if ($this->purchase_order_id) {
                $purchaseOrder = PurchaseOrder::findOrFail($this->purchase_order_id);
                $purchaseOrder->update(['status' => 1]); // Status completado
            }

            DB::commit();

            session()->flash('swal', [
                'icon' => 'success',
                'title' => 'Compra Registrada',
                'text' => 'El inventario ha sido actualizado correctamente.',
            ]);

            return redirect()->route('admin.purchases.index');
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('swal', [
                'icon' => 'error',
                'title' => 'Error',
                'text' => 'Error al guardar: ' . $e->getMessage(),
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

        return view('livewire.admin.purchases.purchases.purchase-create', compact('catalog'));
    }
}

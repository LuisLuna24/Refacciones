<?php

namespace App\Livewire\Admin\Purchases\PurchaseOrder;

use App\Models\Inventory;
use App\Models\Product;
use App\Models\PurchaseOrder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class Edit extends Component
{
    use WithPagination;

    public PurchaseOrder $purchaseOrder;

    // Filtros
    public $search = '';

    // Campos
    public $supplier_id;
    public $warehouse_id;
    public $voucher_type;
    public $serie;
    public $correlative;
    public $date;
    public $total = 0.00;
    public $observation;
    public $category_id;

    // Productos
    public $product_id;
    public $products = [];

    public function mount(PurchaseOrder $purchaseOrder)
    {
        $this->purchaseOrder = $purchaseOrder;
        $this->supplier_id = $purchaseOrder->supplier_id;
        $this->warehouse_id = $purchaseOrder->warehouse_id ?? Auth::user()->warehouse_id;;
        $this->voucher_type = $purchaseOrder->voucher_type;
        $this->serie = $purchaseOrder->serie;
        $this->correlative = $purchaseOrder->correlative;
        $this->date = $purchaseOrder->date->format('Y-m-d');
        $this->observation = $purchaseOrder->observation;
        $this->total = $purchaseOrder->total;

        // Cargar productos existentes y prepararlos para la UX visual de AlpineJS
        $this->products = $purchaseOrder->products->map(function ($product) {
            // Evaluamos cómo se guardó originalmente (Paquete vs Unidad)
            $isPackage = (bool) $product->pivot->ck_pakage;
            $hasPackages = $product->cost_package > 0;

            // La cantidad visual (Lo que el usuario ve en el input)
            // Si es paquete, mostramos cuántos paquetes compró. Si es unidad, cuántas unidades sueltas.
            $uiQuantity = $isPackage ? $product->pivot->quantity_pacage : $product->pivot->quantity;

            return [
                'id' => $product->id,
                'name' => $product->name,
                'sku' => $product->sku ?? '',

                // Datos requeridos por AlpineJS para los Toggles
                'has_packages' => $hasPackages,
                'purchase_type' => $isPackage ? 'package' : 'unit',
                'unit_price' => (float) $product->cost,
                'package_price' => (float) $product->cost_package,
                'units_per_package' => (int) ($product->units_package ?? 1),

                // Datos del carrito actual
                'price' => (float) $product->pivot->price,
                'quantity' => (float) $uiQuantity,
                'subtotal' => (float) $product->pivot->subtotal,
            ];
        })->toArray();
    }

    public function addFromCard($id)
    {
        $this->product_id = $id;
        $this->addProduct();
    }

    /**
     * Aumenta la cantidad de un producto ya agregado a la orden.
     */
    public function incrementFromCard($id)
    {
        $index = collect($this->products)->search(fn ($item) => (int) $item['id'] === (int) $id);

        if ($index === false) {
            $this->addFromCard($id);
            return;
        }

        $this->products[$index]['quantity'] = (float) $this->products[$index]['quantity'] + 1;
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
                'title' => 'Ya en lista',
                'text' => 'El producto ya se encuentra en la orden.',
            ]);
            return;
        }

        $product = Product::find($this->product_id);

        // Lógica de UX al agregar nuevo producto en modo edición
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

    // Eliminamos productos del array visualmente (Opcional, Alpine lo hace con splice, pero es buena práctica tenerlo)
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
            'warehouse_id' => ['required', 'exists:warehouses,id'],
            'observation' => ['nullable', 'string', 'max:255'],
            'products' => ['required', 'array', 'min:1'],
            'products.*.quantity' => ['required', 'numeric', 'min:0.01'],
            'products.*.price' => ['required', 'numeric', 'min:0'],
        ], [], ['supplier_id' => 'proveedor', 'products' => 'productos']);

        DB::beginTransaction();

        try {
            $calculatedTotal = 0;
            $syncData = [];

            foreach ($this->products as $product) {
                // Verificamos el estado actual del toggle (Paquete o Unidad)
                $isPackage = $product['purchase_type'] === 'package';

                // Calculamos la CANTIDAD TOTAL física para el Stock
                $totalPhysicalQuantity = $isPackage
                    ? ($product['quantity'] * $product['units_per_package'])
                    : $product['quantity'];

                $subtotal = $product['quantity'] * $product['price'];
                $calculatedTotal += $subtotal;

                $syncData[$product['id']] = [
                    'quantity' => $totalPhysicalQuantity, // Lo que entrará al almacén
                    'price' => $product['price'],
                    'subtotal' => $subtotal,
                    'ck_pakage' => $isPackage ? 1 : 0,
                    'quantity_pacage' => $isPackage ? $product['quantity'] : null, // Los paquetes tecleados
                ];
            }

            $this->total = $calculatedTotal;

            $this->purchaseOrder->update([
                'voucher_type' => $this->voucher_type,
                'date' => $this->date,
                'supplier_id' => $this->supplier_id,
                'warehouse_id' => $this->warehouse_id,
                'total' => $this->total,
                'observation' => $this->observation,
            ]);

            // Sync se encarga de insertar los nuevos, actualizar los existentes y borrar los que se quitaron
            $this->purchaseOrder->products()->sync($syncData);

            DB::commit();

            session()->flash('swal', [
                'icon' => 'success',
                'title' => '¡Actualizado!',
                'text' => 'La orden de compra ha sido modificada correctamente.',
            ]);

            return redirect()->route('admin.purchase_orders.index');
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('swal', ['icon' => 'error', 'title' => 'Error', 'text' => $e->getMessage()]);
        }
    }

    public function render()
    {
        $warehouseId = $this->warehouse_id;

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

        return view('livewire.admin.purchases.purchase-order.edit', compact('catalog'));
    }
}

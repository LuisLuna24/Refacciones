<div x-data="{
    products: @entangle('products').live,
    total: @entangle('total'),
    removeProduct(index) {
        this.products.splice(index, 1)
    },
    init() {
        this.$watch('products', (newProducts) => {
            let total = 0;
            (newProducts || []).forEach(product => {
                total += (parseFloat(product.quantity) || 0) * (parseFloat(product.price) || 0);
            });
            this.total = total;
        }, { deep: true });
    },
}" class="max-w-[1800px] mx-auto p-2">

    <div class="flex flex-col lg:flex-row gap-6">

        <div class="lg:w-3/5 space-y-4">
            <x-w-card>
                <div class="flex flex-col mb-4 border-b pb-2 dark:border-gray-700">
                    <div>
                        <h2 class="text-xl font-bold text-gray-800 dark:text-gray-200">Selección de Insumos</h2>
                    </div>

                    <div class="flex flex-1 w-full max-w-2xl gap-2">
                        <div class="w-1/3">
                            <x-w-select placeholder="Categoría" wire:model.live="category_id" :async-data="['api' => route('api.categories.index'), 'method' => 'GET']"
                                option-label="name" option-value="id" />
                        </div>
                        <div class="relative w-2/3">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                            <input wire:model.live.debounce.300ms="search" type="text"
                                placeholder="Buscar por nombre o SKU..."
                                class="pl-10 w-full border-gray-300 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-200 rounded-lg shadow-sm focus:ring-emerald-500 focus:border-emerald-500 transition-shadow">
                        </div>
                    </div>
                </div>

                <div
                    class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-3 h-[600px] overflow-y-auto pr-2 custom-scrollbar">
                    @forelse($catalog as $item)
                        @php
                            $stock = intval($item->stock ?? 0);
                            $lowStock = $stock < 5;
                            $hasWarehouse = !empty($warehouse_id);
                        @endphp

                        <div wire:click="addFromCard({{ $item->id }})" wire:key="prod-{{ $item->id }}"
                            class="group relative bg-white dark:bg-gray-800 border rounded-xl p-3 shadow-sm hover:shadow-md cursor-pointer transition-all active:scale-95 flex flex-col justify-between h-full
                            {{ $lowStock && $hasWarehouse ? 'border-yellow-300 dark:border-yellow-700 ring-1 ring-yellow-100 dark:ring-yellow-900/20' : 'border-gray-200 dark:border-gray-700 hover:border-emerald-400' }}">
                            <div>
                                <div class="flex justify-between items-start mb-2">
                                    <span
                                        class="text-[10px] bg-gray-100 dark:bg-gray-700 text-gray-500 px-1.5 py-0.5 rounded font-mono">
                                        {{ $item->sku ?? 'REF' }}
                                    </span>

                                    @if (!$hasWarehouse)
                                        <span class="text-[10px] text-gray-400">?</span>
                                    @elseif($lowStock)
                                        <span
                                            class="text-[10px] font-bold text-yellow-600 bg-yellow-50 px-1 rounded flex items-center gap-1">
                                            ⚠️ Stock: {{ $stock }}
                                        </span>
                                    @else
                                        <span class="text-[10px] font-bold text-emerald-600 flex items-center gap-1">
                                            Stock: {{ $stock }}
                                        </span>
                                    @endif
                                </div>

                                <h3
                                    class="text-sm font-semibold text-gray-700 dark:text-gray-200 line-clamp-2 leading-tight">
                                    {{ $item->name }}
                                </h3>

                                @if ($item->cost > 0)
                                    <p class="text-[10px] text-gray-400 mt-1">
                                        Último costo: ${{ number_format($item->cost, 2) }}
                                    </p>
                                @endif
                            </div>

                            <div class="mt-2 flex justify-end">
                                <span
                                    class="text-emerald-600 bg-emerald-50 dark:bg-emerald-900/30 dark:text-emerald-300 p-1 rounded-full group-hover:bg-emerald-600 group-hover:text-white transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 4v16m8-8H4"></path>
                                    </svg>
                                </span>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full text-center py-10 text-gray-400">
                            Sin resultados.
                        </div>
                    @endforelse
                </div>
                <div class="mt-3">
                    {{ $catalog->links() }}
                </div>
            </x-w-card>
        </div>

        <div class="lg:w-2/5 space-y-4">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border-t-4 border-emerald-500">

                <div
                    class="p-4 border-b dark:border-gray-700 flex justify-between items-center bg-gray-50 dark:bg-gray-900/50 rounded-t-xl">
                    <div>
                        <h2 class="text-lg font-bold text-emerald-700 dark:text-emerald-400 flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4">
                                </path>
                            </svg>
                            Recepción de Compra
                        </h2>
                    </div>
                    <span class="text-sm font-mono font-bold text-black bg-gray-200 px-2 py-1 rounded">
                        {{ $serie }}-{{ $correlative }}
                    </span>
                </div>

                <div class="p-4">
                    <form wire:submit.prevent="save" class="space-y-4">

                        <div
                            class="bg-emerald-50/50 dark:bg-gray-900 p-3 rounded-lg space-y-3 border border-emerald-100 dark:border-gray-700">

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                <div class="col-span-1 md:col-span-2">
                                    <x-w-select label="Proveedor" placeholder="Seleccione un proveedor"
                                        wire:model.live="supplier_id" :async-data="['api' => route('api.suppliers.index'), 'method' => 'GET']" option-label="name"
                                        option-value="id" :clearable="false" :disabled="count($products) > 0" />
                                </div>

                                <x-w-select label="Almacén Destino *" wire:model.live="warehouse_id" :async-data="['api' => route('api.warehouses.index'), 'method' => 'GET']"
                                    option-label="name" option-value="id" :disabled="count($products) > 0" />

                                <x-w-select label="Importar Orden" placeholder="Opcional..."
                                    wire:model.live="purchase_order_id" :async-data="['api' => route('api.purchase-orders.index'), 'method' => 'GET']" option-label="name"
                                    option-value="id" option-description="description" />
                            </div>

                        </div>

                        <div
                            class="border border-emerald-100 dark:border-gray-700 rounded-lg overflow-hidden flex flex-col h-[350px] bg-white dark:bg-gray-800">

                            <div
                                class="grid grid-cols-12 gap-2 p-2 text-xs font-bold uppercase border-b bg-emerald-50 dark:bg-emerald-900/20 text-emerald-800 dark:text-emerald-300">
                                <div class="col-span-4">Producto</div>
                                <div class="col-span-3 text-center">Tipo</div>
                                <div class="col-span-2 text-center">Cant.</div>
                                <div class="col-span-2 text-right">Costo</div>
                                <div class="col-span-1"></div>
                            </div>

                            <div class="overflow-y-auto flex-1 p-2 space-y-2">
                                <template x-for="(product, index) in products" :key="index">
                                    <div
                                        class="grid grid-cols-12 gap-2 items-center bg-white dark:bg-gray-800 p-2.5 rounded-lg shadow-sm border border-gray-100 dark:border-gray-700 transition-all hover:border-emerald-300">

                                        <div class="col-span-4 flex flex-col justify-center">
                                            <div class="text-xs font-bold text-gray-800 dark:text-gray-200 leading-tight line-clamp-2"
                                                x-text="product.name"></div>
                                            <div class="text-[10px] text-emerald-600 font-mono mt-0.5"
                                                x-text="product.sku"></div>
                                        </div>

                                        <div class="col-span-3 flex justify-center items-center">
                                            <template x-if="product.has_packages">
                                                <div
                                                    class="flex items-center space-x-1 bg-gray-50 dark:bg-gray-900 p-1 rounded-full border border-gray-200 dark:border-gray-700">
                                                    <span class="text-[10px] font-bold px-1"
                                                        :class="product.purchase_type === 'unit' ?
                                                            'text-gray-800 dark:text-gray-200' : 'text-gray-400'">Ud</span>

                                                    <button type="button"
                                                        @click="
                                                            product.purchase_type = product.purchase_type === 'unit' ? 'package' : 'unit';
                                                            product.price = product.purchase_type === 'package' ? product.package_price : product.unit_price;
                                                        "
                                                        class="relative inline-flex h-4 w-8 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none"
                                                        :class="product.purchase_type === 'package' ? 'bg-emerald-500' :
                                                            'bg-gray-300 dark:bg-gray-600'">
                                                        <span
                                                            class="pointer-events-none inline-block h-3 w-3 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out"
                                                            :class="product.purchase_type === 'package' ? 'translate-x-4' :
                                                                'translate-x-0'"></span>
                                                    </button>

                                                    <span class="text-[10px] font-bold px-1"
                                                        :class="product.purchase_type === 'package' ? 'text-emerald-600' :
                                                            'text-gray-400'">Paq</span>
                                                </div>
                                            </template>
                                            <template x-if="!product.has_packages">
                                                <span
                                                    class="text-[10px] bg-gray-100 dark:bg-gray-700 text-gray-500 px-2 py-1 rounded font-medium">Solo
                                                    Ud.</span>
                                            </template>
                                        </div>

                                        <div class="col-span-2 flex flex-col items-center">
                                            <input type="number" x-model.number="product.quantity" min="1"
                                                class="w-full text-center text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:ring-emerald-500 focus:border-emerald-500 py-1" />

                                            <template x-if="product.purchase_type === 'package'">
                                                <div class="text-[9px] text-emerald-600 font-bold mt-1 tracking-tight">
                                                    =<span
                                                        x-text="(product.quantity || 0) * product.units_per_package"></span>
                                                    uds
                                                </div>
                                            </template>
                                        </div>

                                        <div class="col-span-2 text-right flex flex-col">
                                            <input type="number" x-model.number="product.price" step="0.01"
                                                class="w-full text-right text-xs border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:ring-emerald-500 focus:border-emerald-500 py-1" />

                                            <div class="text-[10px] font-black text-gray-700 dark:text-gray-300 mt-1">
                                                $<span
                                                    x-text="((parseFloat(product.quantity) || 0) * (parseFloat(product.price) || 0)).toFixed(2)"></span>
                                            </div>
                                        </div>

                                        <div class="col-span-1 flex justify-center items-center">
                                            <button type="button" @click="removeProduct(index)"
                                                class="p-1 text-gray-400 hover:text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-full transition-colors">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                    </path>
                                                </svg>
                                            </button>
                                        </div>

                                    </div>
                                </template>
                            </div>
                        </div>

                        <div
                            class="p-4 bg-emerald-50 dark:bg-emerald-900/20 rounded-xl space-y-3 border border-emerald-100 dark:border-emerald-800">

                            <div class="grid grid-cols-2 gap-3">
                                <x-w-native-select label="Comprobante" wire:model="voucher_type">
                                    <option value="1">Factura</option>
                                    <option value="2">Nota / Ticket</option>
                                </x-w-native-select>
                                <x-w-input type="date" wire:model="date" label="Fecha Emisión" />
                            </div>

                            <x-w-textarea label="Observaciones" wire:model="observation"
                                placeholder="Notas de recepción..." rows="1" />

                            <div
                                class="flex justify-between items-end border-t border-emerald-200 dark:border-emerald-700 pt-3">
                                <span class="text-xs font-bold text-emerald-800 dark:text-emerald-300 uppercase">Total
                                    Compra</span>
                                <span class="text-3xl font-black text-emerald-700 dark:text-emerald-400">
                                    $<span x-text="total.toFixed(2)"></span>
                                </span>
                            </div>
                        </div>

                        <x-w-button type="submit" spinner="save" primary xl
                            class="w-full shadow-md !bg-emerald-600 hover:!bg-emerald-700">
                            REGISTRAR COMPRA
                        </x-w-button>

                        <x-input-error for="products" />
                        <x-input-error for="total" />
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

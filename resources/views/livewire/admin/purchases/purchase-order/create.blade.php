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
    }
}" class="max-w-[1800px] mx-auto p-2">

    <div class="flex flex-col lg:flex-row gap-6">

        {{-- ===== CATÁLOGO ===== --}}
        <div class="lg:w-3/5 space-y-4">
            <x-w-card shadow="lg">
                <div
                    class="flex flex-col lg:flex-row justify-between items-center gap-4 mb-4 border-b pb-4 dark:border-gray-700">
                    <div>
                        <h2 class="text-xl font-bold text-gray-800 dark:text-gray-200">Agregar Productos</h2>
                        <p class="text-xs text-gray-500">Toca una tarjeta para agregar · vuelve a tocarla para sumar 1 más</p>
                    </div>

                    <div class="flex flex-1 w-full max-w-2xl gap-2">
                        <div class="w-1/3">
                            <x-w-select placeholder="Categoría" wire:model.live="category_id" :async-data="['api' => route('api.categories.index'), 'method' => 'GET']"
                                option-label="name" option-value="id" />
                        </div>
                        <div class="relative w-2/3">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                            <input wire:model.live.debounce.300ms="search" type="text"
                                placeholder="Buscar por nombre o SKU..."
                                class="pl-10 w-full border-gray-300 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-200 rounded-lg shadow-sm focus:ring-emerald-500 focus:border-emerald-500 transition-shadow">
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-3 h-[600px] content-start overflow-y-auto pr-2 custom-scrollbar">
                    @forelse($catalog as $item)
                        @php
                            $stock = intval($item->stock ?? 0);
                            $added = collect($products)->firstWhere('id', $item->id);
                            $isAdded = (bool) $added;
                            $addedQty = $isAdded ? round((float) $added['quantity'], 2) : 0;
                        @endphp

                        <div wire:key="prod-{{ $item->id }}"
                            wire:click="{{ $isAdded ? 'incrementFromCard' : 'addFromCard' }}({{ $item->id }})"
                            @keydown.enter="$wire.{{ $isAdded ? 'incrementFromCard' : 'addFromCard' }}({{ $item->id }})"
                            role="button" tabindex="0"
                            title="{{ $item->name }}"
                            class="group relative flex flex-col justify-between h-full rounded-xl border bg-white dark:bg-gray-800 p-3 shadow-sm transition-all
                            {{ $isAdded
                                ? 'cursor-pointer ring-2 ring-emerald-500 border-emerald-500 bg-emerald-50/40 dark:bg-emerald-900/10 hover:shadow-md'
                                : 'cursor-pointer border-gray-200 dark:border-gray-700 focus:outline-none focus:ring-2 focus:ring-emerald-400 hover:-translate-y-0.5 hover:shadow-md hover:border-emerald-400' }}">

                            @if ($isAdded)
                                <div class="absolute -top-2 -right-2 z-20" title="{{ $addedQty }} en la orden">
                                    <span
                                        class="inline-flex h-6 min-w-[1.5rem] items-center justify-center rounded-full bg-emerald-500 px-1.5 text-[11px] font-black text-white shadow-md ring-2 ring-white dark:ring-gray-800">
                                        +{{ $addedQty }}
                                    </span>
                                </div>
                            @endif

                            <div>
                                <div class="flex items-start justify-between gap-2 mb-2">
                                    <span class="rounded px-1.5 py-0.5 font-mono text-[10px] {{ $isAdded ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-400' : 'bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-300' }}">
                                        {{ $item->sku ?? '---' }}
                                    </span>

                                    @if ($isAdded)
                                        <span
                                            class="inline-flex items-center gap-1 rounded px-1.5 py-0.5 text-[9px] font-bold text-white bg-emerald-500 shadow-sm">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                            EN ORDEN
                                        </span>
                                    @else
                                        <span class="rounded px-1.5 py-0.5 text-[10px] font-bold text-gray-500 dark:text-gray-400">Stock: {{ $stock }}</span>
                                    @endif
                                </div>

                                <h3 title="{{ $item->name }}"
                                    class="text-sm font-semibold text-gray-700 dark:text-gray-200 leading-snug break-words line-clamp-3">
                                    {{ $item->name }}
                                </h3>

                                <p class="mt-1 text-[10px] text-gray-400">
                                    Costo {{ $item->cost_package > 0 ? 'Paquete' : 'Unidad' }}:
                                    ${{ number_format($item->cost_package > 0 ? $item->cost_package : $item->cost, 2) }}
                                </p>
                            </div>

                            <div class="mt-2 flex items-center justify-end">
                                @if ($isAdded)
                                    <span class="text-[11px] font-bold text-emerald-600 dark:text-emerald-400">{{ $addedQty }} en orden</span>
                                    <span class="ml-auto inline-flex h-6 w-6 items-center justify-center rounded-full bg-emerald-500 text-white shadow-sm" title="Aumentar cantidad">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path>
                                        </svg>
                                    </span>
                                @else
                                    <span class="inline-flex h-6 w-6 items-center justify-center rounded-full bg-emerald-100 text-emerald-600 dark:bg-emerald-900/40 dark:text-emerald-400 group-hover:bg-emerald-500 group-hover:text-white transition-colors" title="Agregar a la orden">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path>
                                        </svg>
                                    </span>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full flex flex-col items-center justify-center py-10 text-gray-400 border-2 border-dashed border-gray-200 dark:border-gray-700 rounded-xl gap-2">
                            <svg class="w-10 h-10 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            <p class="text-sm font-medium">{{ $supplier_id ? 'No se encontró ningún producto' : 'Seleccione un proveedor' }}</p>
                        </div>
                    @endforelse
                </div>

                <div class="mt-3">
                    {{ $catalog->links() }}
                </div>
            </x-w-card>
        </div>

        {{-- ===== DETALLES DE LA ORDEN ===== --}}
        <div class="lg:w-2/5 space-y-4">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border-t-4 border-emerald-500">

                <div class="p-4 border-b dark:border-gray-700 flex justify-between items-center bg-gray-50 dark:bg-gray-900/50 rounded-t-xl">
                    <div class="flex items-center gap-2">
                        <div class="p-1.5 bg-emerald-100 dark:bg-emerald-900/40 rounded-lg text-emerald-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-lg font-bold text-gray-800 dark:text-emerald-400">Creando orden</h2>
                            <span class="text-[10px] text-gray-400 uppercase block font-bold">Documento N°</span>
                        </div>
                    </div>
                    <span class="text-sm font-mono font-bold text-black dark:text-white bg-gray-200 dark:bg-gray-700 px-2 py-1 rounded">
                        {{ $serie }}-{{ $correlative }}
                    </span>
                </div>

                <div class="p-4">
                    <form wire:submit.prevent="save" class="space-y-4">

                        <div class="bg-emerald-50/30 dark:bg-gray-900 p-3 rounded-lg space-y-3 border border-emerald-100 dark:border-gray-700">
                            <x-w-select label="Proveedor" placeholder="Seleccione un proveedor"
                                wire:model.live="supplier_id" :async-data="['api' => route('api.suppliers.index'), 'method' => 'GET']" option-label="name" option-value="id"
                                :clearable="false" :disabled="count($products) > 0" />

                            <div class="grid grid-cols-2 gap-3">
                                <x-w-select label="Almacén Destino" placeholder="Seleccione un almacen"
                                    wire:model="warehouse_id" :async-data="['api' => route('api.warehouses.index'), 'method' => 'GET']" option-label="name" option-value="id"
                                    :clearable="false" />
                                <x-w-input type="date" wire:model="date" label="Fecha" />
                            </div>
                        </div>

                        <div class="border border-emerald-100 dark:border-gray-700 rounded-lg overflow-hidden flex flex-col h-[350px] bg-white dark:bg-gray-800">
                            <div class="grid grid-cols-12 gap-2 p-2 text-xs font-bold uppercase border-b bg-emerald-50 dark:bg-emerald-900/20 text-emerald-800 dark:text-emerald-300">
                                <div class="col-span-5 pl-1">Producto</div>
                                <div class="col-span-3 text-center">Tipo</div>
                                <div class="col-span-2 text-center">Cant.</div>
                                <div class="col-span-2 text-right">Costo</div>
                            </div>

                            <div class="overflow-y-auto flex-1 p-2 space-y-2">
                                <template x-for="(product, index) in products" :key="index">
                                    <div class="grid grid-cols-12 gap-2 items-center bg-white dark:bg-gray-800 p-2.5 rounded-lg shadow-sm border border-gray-100 dark:border-gray-700 transition-all hover:border-emerald-300">

                                        <div class="col-span-5 flex items-start justify-between gap-2 min-w-0">
                                            <div class="flex flex-col min-w-0">
                                                <div class="text-xs font-bold text-gray-800 dark:text-gray-200 leading-snug break-words line-clamp-2" :title="product.name" x-text="product.name"></div>
                                                <div class="text-[10px] text-emerald-600 font-mono mt-0.5 truncate" x-text="product.sku"></div>
                                            </div>
                                            <button type="button" @click="removeProduct(index)"
                                                class="p-1 shrink-0 text-gray-400 hover:text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-full transition-colors">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                            </button>
                                        </div>

                                        <div class="col-span-3 flex justify-center items-center">
                                            <template x-if="product.has_packages">
                                                <div class="flex items-center space-x-1 bg-gray-50 dark:bg-gray-900 p-1 rounded-full border border-gray-200 dark:border-gray-700">
                                                    <span class="text-[10px] font-bold px-1"
                                                        :class="product.purchase_type === 'unit' ? 'text-gray-800 dark:text-gray-200' : 'text-gray-400'">Ud</span>

                                                    <button type="button"
                                                        @click="
                                                            product.purchase_type = product.purchase_type === 'unit' ? 'package' : 'unit';
                                                            product.price = product.purchase_type === 'package' ? product.package_price : product.unit_price;
                                                        "
                                                        class="relative inline-flex h-4 w-8 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none"
                                                        :class="product.purchase_type === 'package' ? 'bg-emerald-500' : 'bg-gray-300 dark:bg-gray-600'">
                                                        <span class="pointer-events-none inline-block h-3 w-3 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out"
                                                            :class="product.purchase_type === 'package' ? 'translate-x-4' : 'translate-x-0'"></span>
                                                    </button>

                                                    <span class="text-[10px] font-bold px-1"
                                                        :class="product.purchase_type === 'package' ? 'text-emerald-600' : 'text-gray-400'">Paq</span>
                                                </div>
                                            </template>
                                            <template x-if="!product.has_packages">
                                                <span class="text-[10px] bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-300 px-2 py-1 rounded font-medium">Solo Ud.</span>
                                            </template>
                                        </div>

                                        <div class="col-span-2 flex flex-col items-center">
                                            <input type="number" x-model.number="product.quantity" min="1"
                                                class="w-full text-center text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:ring-emerald-500 focus:border-emerald-500 py-1" />

                                            <template x-if="product.purchase_type === 'package'">
                                                <div class="text-[9px] text-emerald-600 font-bold mt-1 tracking-tight">=<span x-text="(product.quantity || 0) * product.units_per_package"></span> uds</div>
                                            </template>
                                        </div>

                                        <div class="col-span-2 text-right flex flex-col">
                                            <input type="number" x-model.number="product.price" step="0.01"
                                                class="w-full text-right text-xs border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:ring-emerald-500 focus:border-emerald-500 py-1" />

                                            <div class="text-[10px] font-black text-gray-700 dark:text-gray-300 mt-1">$<span x-text="((parseFloat(product.quantity) || 0) * (parseFloat(product.price) || 0)).toFixed(2)"></span></div>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>

                        <div class="p-4 bg-emerald-50 dark:bg-emerald-900/20 rounded-xl space-y-3 border border-emerald-100 dark:border-emerald-800">
                            <x-w-native-select label="Tipo Comp." wire:model="voucher_type">
                                <option value="1">Factura</option>
                                <option value="2">Nota</option>
                            </x-w-native-select>

                            <x-w-textarea label="Notas" wire:model="observation" placeholder="..." rows="1" />

                            <div class="flex justify-between items-end border-t border-emerald-200 dark:border-emerald-700 pt-3">
                                <span class="text-xs font-bold text-emerald-800 dark:text-emerald-300 uppercase">Total Orden</span>
                                <span class="text-3xl font-black text-emerald-700 dark:text-emerald-400">$<span x-text="total.toFixed(2)"></span></span>
                            </div>
                        </div>

                        <x-w-button type="submit" spinner="save" primary xl class="w-full shadow-md !bg-emerald-600 hover:!bg-emerald-700">
                            GUARDAR CAMBIOS
                        </x-w-button>

                        <x-input-error for="products" />
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
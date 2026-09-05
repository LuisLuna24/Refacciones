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

        {{-- ===== CATÁLOGO ===== --}}
        <div class="lg:w-3/5 space-y-4">
            <x-w-card shadow="lg">
                <div
                    class="flex flex-col lg:flex-row justify-between items-center gap-4 mb-4 border-b pb-4 dark:border-gray-700">
                    <div>
                        <h2 class="text-xl font-bold text-gray-800 dark:text-gray-200">Catálogo para Cotizar</h2>
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

                <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-4 h-[650px] content-start overflow-y-auto pr-2 custom-scrollbar">
                    @forelse($catalog as $item)
                        @php
                            $stock = intval($item->stock ?? 0);
                            $hasStock = $stock > 0;
                            $hasWarehouse = !empty($warehouse_id);
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
                                <div class="absolute -top-2 -right-2 z-20" title="{{ $addedQty }} en la cotización">
                                    <span
                                        class="inline-flex h-6 min-w-[1.5rem] items-center justify-center rounded-full bg-emerald-500 px-1.5 text-[11px] font-black text-white shadow-md ring-2 ring-white dark:ring-gray-800">
                                        +{{ $addedQty }}
                                    </span>
                                </div>
                            @endif

                            <div>
                                <div class="flex items-start justify-between gap-2 mb-2">
                                    <span
                                        class="rounded-md px-1.5 py-0.5 font-mono text-[11px] font-bold {{ $isAdded ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-400' : 'bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-300' }}">
                                        ${{ number_format($item->price, 2) }}
                                    </span>

                                    @if (!$hasWarehouse)
                                        <span class="rounded px-1.5 py-0.5 text-[10px] font-bold text-gray-500 bg-gray-100 dark:bg-gray-700 dark:text-gray-300">Ref. stock</span>
                                    @elseif($hasStock)
                                        <span class="rounded px-1.5 py-0.5 text-[10px] font-bold text-emerald-600 bg-emerald-50 dark:text-emerald-400 dark:bg-emerald-900/40">{{ $stock }} disp.</span>
                                    @else
                                        <span class="rounded px-1.5 py-0.5 text-[10px] font-bold text-orange-600 bg-orange-50 dark:bg-orange-900/30 dark:text-orange-400">Sin stock</span>
                                    @endif
                                </div>

                                <h3 title="{{ $item->name }}"
                                    class="text-sm font-semibold text-gray-700 dark:text-gray-200 leading-snug break-words line-clamp-3">
                                    {{ $item->name }}
                                </h3>
                                <p title="{{ $item->sku ?? 'S/SKU' }}" class="mt-1 font-mono text-[10px] text-gray-400 uppercase truncate">{{ $item->sku ?? 'S/SKU' }}</p>
                            </div>

                            <div class="mt-3 flex items-center justify-end">
                                @if ($isAdded)
                                    <span class="text-[11px] font-bold text-emerald-600 dark:text-emerald-400">{{ $addedQty }} en lista</span>
                                    <span class="ml-auto inline-flex h-6 w-6 items-center justify-center rounded-full bg-emerald-500 text-white shadow-sm" title="Aumentar cantidad">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path>
                                        </svg>
                                    </span>
                                @else
                                    <span class="inline-flex h-6 w-6 items-center justify-center rounded-full bg-emerald-100 text-emerald-600 dark:bg-emerald-900/40 dark:text-emerald-400 group-hover:bg-emerald-500 group-hover:text-white transition-colors" title="Agregar a la cotización">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path>
                                        </svg>
                                    </span>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full flex flex-col items-center justify-center py-20 text-gray-400 border-2 border-dashed border-gray-200 dark:border-gray-700 rounded-xl">
                            <svg class="w-12 h-12 mb-3 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            <p class="font-medium">No hay productos que coincidan con la búsqueda.</p>
                        </div>
                    @endforelse
                </div>

                <div class="mt-4 border-t pt-4">
                    {{ $catalog->links() }}
                </div>
            </x-w-card>
        </div>

        {{-- ===== DETALLES DE LA COTIZACIÓN ===== --}}
        <div class="lg:w-2/5 space-y-4">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-xl border-t-4 border-emerald-500 overflow-hidden">

                <div
                    class="p-4 border-b dark:border-gray-700 flex justify-between items-center bg-gray-50 dark:bg-gray-900/50">
                    <div class="flex items-center gap-2">
                        <div class="p-2 bg-emerald-100 dark:bg-emerald-900/40 rounded-lg text-emerald-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-lg font-bold text-gray-800 dark:text-emerald-400 uppercase tracking-tight">Cotización</h2>
                            <span class="text-[10px] text-gray-400 uppercase block font-bold">Documento N°</span>
                        </div>
                    </div>
                    <span class="text-sm font-mono font-black text-emerald-700 dark:text-emerald-300 bg-emerald-100 dark:bg-emerald-900/40 px-3 py-1 rounded-full">
                        {{ $serie }}-{{ $correlative }}
                    </span>
                </div>

                <div class="p-4">
                    <form wire:submit.prevent="save" class="space-y-4">

                        <div class="bg-emerald-50/30 dark:bg-gray-900/50 p-3 rounded-lg border border-emerald-100 dark:border-gray-700 space-y-3">
                            <div class="grid grid-cols-2 gap-3">
                                <x-w-select label="Almacén (Ref. Stock)" placeholder="Seleccionar..."
                                    wire:model.live="warehouse_id" :async-data="['api' => route('api.warehouses.index'), 'method' => 'GET']" option-label="name"
                                    option-value="id" :clearable="false" />
                                <x-w-input type="date" wire:model="date" label="Fecha Validez" />
                            </div>

                            <div class="grid grid-cols-2 gap-3">
                                <x-w-native-select label="Precios basados en" wire:model="voucher_type">
                                    <option value="1">Factura (Incl. Impuestos)</option>
                                    <option value="2">Nota de Venta</option>
                                </x-w-native-select>
                                <x-w-input wire:model="serie" label="Serie Ref." disabled />
                            </div>

                            <x-w-native-select label="Metodo de pago" placeholder="Selecciona un metodo de pago"
                                wire:model="payment_method">
                                <option value="1">Efectivo</option>
                                <option value="2">Tarjeta</option>
                                <option value="3">Transferencia</option>
                                <option value="4">Paypal</option>
                            </x-w-native-select>
                        </div>

                        <div class="border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden flex flex-col h-[320px] bg-white dark:bg-gray-800 shadow-inner">
                            <div class="grid grid-cols-12 gap-2 p-2 text-[10px] font-bold uppercase border-b bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400">
                                <div class="col-span-6 pl-1">Producto / Concepto</div>
                                <div class="col-span-2 text-center">Cant.</div>
                                <div class="col-span-3 text-right">Total</div>
                                <div class="col-span-1"></div>
                            </div>

                            <div class="overflow-y-auto flex-1 p-2 space-y-2 custom-scrollbar">
                                <template x-for="(product, index) in products" :key="index">
                                    <div class="grid grid-cols-12 gap-2 items-center bg-white dark:bg-gray-900 p-2 rounded-lg shadow-sm border border-gray-100 dark:border-gray-700 hover:border-emerald-300 transition-all">

                                        <div class="col-span-6 flex flex-col min-w-0">
                                            <span class="text-xs font-bold text-gray-800 dark:text-gray-200 break-words line-clamp-2" :title="product.name" x-text="product.name"></span>
                                            <div class="flex items-center gap-1 mt-1">
                                                <span class="text-[9px] text-gray-400">$</span>
                                                <input type="number" x-model.number="product.price" step="0.01"
                                                    class="w-full h-5 text-xs border-0 border-b border-gray-200 dark:border-gray-600 dark:bg-transparent focus:ring-0 focus:border-emerald-500 p-0 text-emerald-600 font-bold"
                                                    placeholder="0.00">
                                            </div>
                                        </div>

                                        <div class="col-span-2">
                                            <div class="flex items-center justify-between bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-600 rounded-md overflow-hidden h-7">
                                                <button type="button" @click="product.quantity = Math.max(1, (parseFloat(product.quantity) || 1) - 1)" class="w-6 h-full flex items-center justify-center text-gray-500 hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M20 12H4"></path></svg>
                                                </button>
                                                <input type="number" x-model.number="product.quantity" min="1"
                                                    class="w-full text-center text-[11px] font-bold bg-transparent border-none p-0 focus:ring-0 [-moz-appearance:_textfield] [&::-webkit-outer-spin-button]:m-0 [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:m-0 [&::-webkit-inner-spin-button]:appearance-none" />
                                                <button type="button" @click="product.quantity = (parseFloat(product.quantity) || 1) + 1" class="w-6 h-full flex items-center justify-center text-emerald-600 hover:bg-emerald-100 dark:hover:bg-emerald-900/50 transition-colors">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"></path></svg>
                                                </button>
                                            </div>
                                        </div>

                                        <div class="col-span-3 text-right">
                                            <span class="text-xs font-black text-gray-800 dark:text-gray-100">
                                                $<span x-text="((parseFloat(product.quantity) || 0) * (parseFloat(product.price) || 0)).toFixed(2)"></span>
                                            </span>
                                        </div>

                                        <div class="col-span-1 flex justify-center">
                                            <button type="button" @click="removeProduct(index)"
                                                class="p-1 text-red-400 hover:text-red-600 hover:bg-red-50 dark:hover:bg-red-900/30 rounded-full transition-all">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                </template>

                                <template x-if="products.length === 0">
                                    <div class="h-full flex flex-col items-center justify-center text-gray-400 opacity-60 gap-2">
                                        <svg class="w-10 h-10 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                        <p class="text-xs font-medium">Selecciona ítems para la cotización</p>
                                    </div>
                                </template>
                            </div>
                        </div>

                        <div class="bg-gray-50 dark:bg-gray-900/50 p-4 rounded-xl border border-gray-100 dark:border-gray-700 space-y-3">
                            <x-w-select label="Cliente Potencial" placeholder="Buscar cliente..."
                                wire:model="customer_id" :async-data="['api' => route('api.customers.index'), 'method' => 'GET']" option-label="name" option-value="id" />

                            <x-w-textarea label="Condiciones Especiales" wire:model="observation"
                                placeholder="Validez de oferta, tiempo de entrega, formas de pago..."
                                rows="2" />

                            <div class="flex justify-between items-center border-t border-gray-200 dark:border-gray-700 pt-3">
                                <div class="flex flex-col">
                                    <span class="text-[10px] font-bold text-gray-400 uppercase">Subtotal Neto</span>
                                    <span class="text-xs text-gray-500 italic">Precios sujetos a cambio</span>
                                </div>
                                <div class="text-right">
                                    <span class="text-3xl font-black text-emerald-600 dark:text-emerald-500">
                                        $<span x-text="total.toFixed(2)"></span>
                                    </span>
                                    <span class="text-[10px] block font-bold text-gray-400 tracking-widest">MXN TOTAL</span>
                                </div>
                            </div>
                        </div>

                        <x-w-button type="submit" spinner="save" primary xl
                            class="w-full shadow-lg !bg-emerald-600 hover:!bg-emerald-700 !border-none font-bold">
                            <div class="flex items-center justify-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                GENERAR COTIZACIÓN
                            </div>
                        </x-w-button>

                        <x-input-error for="products" />
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
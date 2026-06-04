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

        <!-- LADO IZQUIERDO: Catálogo de Productos -->
        <div class="lg:w-3/5 space-y-4">
            <x-w-card shadow="lg">
                <div class="flex flex-col mb-4 border-b pb-4 dark:border-gray-700 gap-4">
                    <div>
                        <h2 class="text-xl font-bold text-gray-800 dark:text-gray-200">Catálogo para Cotizar</h2>
                        <p class="text-xs text-gray-500 italic">Haz clic en un producto para agregarlo a la propuesta</p>
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

                <!-- Grid de Productos -->
                <div
                    class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-4 h-[650px] overflow-y-auto pr-2 custom-scrollbar">
                    @forelse($catalog as $item)
                        @php
                            $stock = intval($item->stock ?? 0);
                            $hasStock = $stock > 0;
                            $hasWarehouse = !empty($warehouse_id);
                            $isAdded = collect($products)->contains('id', $item->id);
                        @endphp

                        <div wire:click="addFromCard({{ $item->id }})" wire:key="prod-{{ $item->id }}"
                            class="group relative bg-white dark:bg-gray-800 border rounded-xl p-3 shadow-sm transition-all active:scale-95 flex flex-col justify-between h-full
                            {{ $isAdded ? 'ring-2 ring-emerald-500 border-emerald-500' : 'border-gray-200 dark:border-gray-700 hover:border-emerald-400' }}
                            {{ !$hasStock ? 'bg-gray-50/50' : '' }} cursor-pointer hover:shadow-md">

                            @if ($isAdded)
                                <div class="absolute -top-2 -right-2 z-20">
                                    <span
                                        class="flex h-6 w-6 items-center justify-center rounded-full bg-emerald-500 text-white shadow-md ring-2 ring-white">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                                d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    </span>
                                </div>
                            @endif

                            <div>
                                <div class="flex justify-between items-start mb-2">
                                    <span
                                        class="text-[11px] font-bold text-emerald-700 bg-emerald-50 dark:bg-emerald-900/30 dark:text-emerald-400 px-2 py-0.5 rounded">
                                        ${{ number_format($item->price, 2) }}
                                    </span>

                                    @if (!$hasWarehouse)
                                        <span class="text-[10px] text-gray-400 font-medium">Ref. stock</span>
                                    @elseif($hasStock)
                                        <span class="text-[10px] font-bold text-emerald-600 flex items-center gap-1">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                            {{ $stock }}
                                        </span>
                                    @else
                                        <span class="text-[10px] font-bold text-orange-500 flex items-center gap-1">
                                            <span class="w-1.5 h-1.5 rounded-full bg-orange-500"></span> 0
                                        </span>
                                    @endif
                                </div>

                                <h3
                                    class="text-sm font-semibold text-gray-700 dark:text-gray-200 line-clamp-2 leading-tight">
                                    {{ $item->name }}
                                </h3>
                                <p class="text-[10px] text-gray-400 mt-1 font-mono uppercase">{{ $item->sku }}</p>
                            </div>

                            <div class="mt-3 flex justify-end">
                                <span
                                    class="text-emerald-600 bg-emerald-50 dark:bg-emerald-900/30 p-1 rounded-full group-hover:bg-emerald-600 group-hover:text-white transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 4v16m8-8H4"></path>
                                    </svg>
                                </span>
                            </div>
                        </div>
                    @empty
                        <div
                            class="col-span-full text-center py-20 text-gray-400 border-2 border-dashed border-gray-100 rounded-xl">
                            <p>No hay productos que coincidan con la búsqueda.</p>
                        </div>
                    @endforelse
                </div>

                <div class="mt-4 border-t pt-4">
                    {{ $catalog->links() }}
                </div>
            </x-w-card>
        </div>

        <!-- LADO DERECHO: Detalles de la Cotización -->
        <div class="lg:w-2/5 space-y-4">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-xl border-t-4 border-emerald-500 overflow-hidden">

                <!-- Cabecera de Serie y Correlativo -->
                <div
                    class="p-4 border-b dark:border-gray-700 flex justify-between items-center bg-gray-50 dark:bg-gray-900/50">
                    <div class="flex items-center gap-2">
                        <div class="p-2 bg-emerald-100 dark:bg-emerald-900/40 rounded-lg text-emerald-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z">
                                </path>
                            </svg>
                        </div>
                        <h2 class="text-lg font-bold text-gray-800 dark:text-emerald-400 uppercase tracking-tight">
                            Cotización</h2>
                    </div>
                    <div class="text-right">
                        <span class="text-[10px] text-gray-400 uppercase block font-bold">Documento N°</span>
                        <span
                            class="text-sm font-mono font-black text-emerald-700 bg-emerald-100 px-3 py-1 rounded-full">
                            {{ $serie }}-{{ $correlative }}
                        </span>
                    </div>
                </div>

                <div class="p-4">
                    <form wire:submit.prevent="save" class="space-y-4">

                        <!-- Configuración General -->
                        <div
                            class="bg-emerald-50/30 dark:bg-gray-900/50 p-3 rounded-lg border border-emerald-100 dark:border-gray-700 space-y-3">
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

                            <div class="col-span-2">
                                <x-w-native-select label="Metodo de pago" placeholder="Selecciona un metodo de pago"
                                    wire:model="payment_method">
                                    <option value="1">Efectivo</option>
                                    <option value="2">Tarjeta</option>
                                    <option value="3">Transferencia</option>
                                    <option value="4">Paypal</option>
                                </x-w-native-select>
                            </div>
                        </div>

                        <!-- Lista de Ítems Cotizados -->
                        <div
                            class="border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden flex flex-col h-[320px] bg-white dark:bg-gray-800 shadow-inner">
                            <div
                                class="grid grid-cols-12 gap-2 p-2 text-[10px] font-bold uppercase border-b bg-gray-100 dark:bg-gray-700 text-gray-600">
                                <div class="col-span-6">Producto / Concepto</div>
                                <div class="col-span-2 text-center">Cant.</div>
                                <div class="col-span-3 text-right">Total</div>
                                <div class="col-span-1"></div>
                            </div>

                            <div class="overflow-y-auto flex-1 p-2 space-y-2 custom-scrollbar">
                                <template x-for="(product, index) in products" :key="index">
                                    <div
                                        class="grid grid-cols-12 gap-2 items-center bg-white dark:bg-gray-900 p-2 rounded shadow-sm border border-gray-100 dark:border-gray-700 hover:border-emerald-300">

                                        <div class="col-span-6 flex flex-col">
                                            <span class="text-xs font-bold text-gray-800 dark:text-gray-200 truncate"
                                                x-text="product.name"></span>
                                            <div class="flex items-center gap-1 mt-1">
                                                <span class="text-[9px] text-gray-400">$</span>
                                                <input type="number" x-model.number="product.price" step="0.01"
                                                    class="w-full h-5 text-xs border-0 border-b border-gray-200 dark:border-gray-600 dark:bg-transparent focus:ring-0 focus:border-emerald-500 p-0 text-emerald-600 font-bold"
                                                    placeholder="0.00">
                                            </div>
                                        </div>

                                        <div class="col-span-2">
                                            <input type="number" x-model.number="product.quantity" min="1"
                                                class="w-full text-center text-xs border-gray-200 dark:border-gray-600 dark:bg-gray-800 rounded p-1 focus:ring-emerald-500" />
                                        </div>

                                        <div class="col-span-3 text-right">
                                            <span class="text-xs font-black text-gray-800 dark:text-gray-100">
                                                $<span
                                                    x-text="((parseFloat(product.quantity) || 0) * (parseFloat(product.price) || 0)).toFixed(2)"></span>
                                            </span>
                                        </div>

                                        <div class="col-span-1 flex justify-center">
                                            <button type="button" @click="removeProduct(index)"
                                                class="p-1 text-red-400 hover:text-red-600 hover:bg-red-50 rounded-full transition-all">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                </template>

                                <template x-if="products.length === 0">
                                    <div
                                        class="h-full flex flex-col items-center justify-center text-gray-400 opacity-40">
                                        <p class="text-[10px] italic">Selecciona ítems para la cotización</p>
                                    </div>
                                </template>
                            </div>
                        </div>

                        <!-- Cliente y Notas -->
                        <div
                            class="bg-gray-50 dark:bg-gray-900/50 p-4 rounded-xl border border-gray-100 dark:border-gray-700 space-y-3">
                            <x-w-select label="Cliente Potencial" placeholder="Buscar cliente..."
                                wire:model="customer_id" :async-data="['api' => route('api.customers.index'), 'method' => 'GET']" option-label="name" option-value="id" />

                            <x-w-textarea label="Condiciones Especiales" wire:model="observation"
                                placeholder="Validez de oferta, tiempo de entrega, formas de pago..."
                                rows="2" />

                            <div
                                class="flex justify-between items-center border-t border-gray-200 dark:border-gray-700 pt-3">
                                <div class="flex flex-col">
                                    <span class="text-[10px] font-bold text-gray-400 uppercase">Subtotal Neto</span>
                                    <span class="text-xs text-gray-500 italic">Precios sujetos a cambio</span>
                                </div>
                                <div class="text-right">
                                    <span class="text-3xl font-black text-emerald-600 dark:text-emerald-500">
                                        $<span x-text="total.toFixed(2)"></span>
                                    </span>
                                    <span class="text-[10px] block font-bold text-gray-400 tracking-widest">MXN
                                        TOTAL</span>
                                </div>
                            </div>
                        </div>

                        <x-w-button type="submit" spinner="save" primary xl
                            class="w-full shadow-lg !bg-emerald-600 hover:!bg-emerald-700 !border-none font-bold">
                            <div class="flex items-center justify-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                    </path>
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

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
                <div
                    class="flex flex-col md:flex-row justify-between items-center mb-4 border-b pb-4 dark:border-gray-700 gap-4">
                    <div>
                        <h2 class="text-xl font-bold text-gray-800 dark:text-gray-200">Catálogo de Ventas</h2>
                        <p class="text-xs text-gray-500">Selecciona productos para la nota actual</p>
                    </div>

                    <div class="flex flex-1 w-full max-w-2xl gap-2">
                        <div class="w-1/3">
                            <x-w-select placeholder="Categoría" wire:model.live="category_id" :async-data="['api' => route('api.categories.index'), 'method' => 'POST']"
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
                                class="pl-10 w-full border-gray-300 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-200 rounded-md shadow-sm focus:ring-emerald-500 focus:border-emerald-500">
                        </div>
                    </div>
                </div>

                <div
                    class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-4 h-[650px] overflow-y-auto pr-2 custom-scrollbar">
                    @forelse($catalog as $item)
                        @php
                            $stock = intval($item->stock ?? 0);
                            $hasStock = $stock > 0;
                            $lowStock = $stock < 5;
                            $hasWarehouse = !empty($warehouse_id);
                            $isAdded = collect($products)->contains('id', $item->id);
                        @endphp

                        <div wire:key="prod-{{ $item->id }}"
                            @if ($hasStock && $hasWarehouse) wire:click="addFromCard({{ $item->id }})" @endif
                            class="group relative bg-white dark:bg-gray-800 border rounded-xl p-3 shadow-sm transition-all active:scale-95 flex flex-col justify-between h-full
                            {{ !$hasStock || !$hasWarehouse ? 'opacity-60 cursor-not-allowed bg-gray-50' : 'cursor-pointer hover:shadow-md hover:border-emerald-400' }}
                            {{ $isAdded ? 'ring-2 ring-emerald-500 border-emerald-500' : 'border-gray-200 dark:border-gray-700' }}">

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
                                        <span class="text-[10px] text-orange-500 font-bold">⚠️ Selecc. Almacén</span>
                                    @elseif($hasStock)
                                        <span
                                            class="text-[10px] font-bold {{ $lowStock ? 'text-yellow-600' : 'text-emerald-600' }}">
                                            {{ $lowStock ? '⚠️' : '✅' }} Stock: {{ $stock }}
                                        </span>
                                    @else
                                        <span class="text-[10px] font-bold text-red-600">🚫 Agotado</span>
                                    @endif
                                </div>

                                <h3
                                    class="text-sm font-semibold text-gray-700 dark:text-gray-200 line-clamp-2 leading-tight">
                                    {{ $item->name }}
                                </h3>
                                <p class="text-[10px] text-gray-400 mt-1 font-mono">{{ $item->sku ?? 'S/SKU' }}</p>
                            </div>

                            <div class="mt-3 flex justify-end">
                                <button
                                    class="text-emerald-600 bg-emerald-50 dark:bg-emerald-900/30 p-1.5 rounded-full group-hover:bg-emerald-600 group-hover:text-white transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 4v16m8-8H4"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full flex flex-col items-center justify-center py-20 text-gray-400">
                            <svg class="w-16 h-16 mb-4 opacity-20" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                            </svg>
                            <p class="font-medium">No se encontraron productos disponibles</p>
                        </div>
                    @endforelse
                </div>

                <div class="mt-4 border-t pt-4">
                    {{ $catalog->links() }}
                </div>
            </x-w-card>
        </div>

        <!-- LADO DERECHO: Carrito/Resumen de Venta -->
        <div class="lg:w-2/5 space-y-4">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-xl border-t-4 border-emerald-500 overflow-hidden">

                <!-- Cabecera de Documento con Serie -->
                <div
                    class="p-4 border-b dark:border-gray-700 flex justify-between items-center bg-gray-50 dark:bg-gray-900/50">
                    <div class="flex items-center gap-2">
                        <div class="p-2 bg-emerald-100 dark:bg-emerald-900/40 rounded-lg text-emerald-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z">
                                </path>
                            </svg>
                        </div>
                        <h2 class="text-lg font-bold text-gray-800 dark:text-emerald-400">Nueva Venta</h2>
                    </div>
                    <div class="text-right">
                        <span class="text-[10px] text-gray-500 uppercase block font-bold">Comprobante</span>
                        <span
                            class="text-sm font-mono font-black text-emerald-700 bg-emerald-100 px-3 py-1 rounded-full">
                            {{ $serie }}-{{ $correlative }}
                        </span>
                    </div>
                </div>

                <div class="p-4">
                    <form wire:submit.prevent="save" class="space-y-4">

                        <!-- Configuración de Cabecera -->
                        <div
                            class="bg-emerald-50/50 dark:bg-gray-900/50 p-3 rounded-lg border border-emerald-100 dark:border-gray-700 grid grid-cols-2 gap-3">
                            <div class="col-span-2">
                                <x-w-select label="Cliente" placeholder="Público en General"
                                    wire:model.live="customer_id" :async-data="['api' => route('api.customers.index'), 'method' => 'POST']" option-label="name"
                                    option-value="id" />
                            </div>
                            <x-w-select label="Almacén *" wire:model.live="warehouse_id" :async-data="['api' => route('api.warehouses.index'), 'method' => 'POST']"
                                option-label="name" option-value="id" :clearable="false" />

                            <x-w-native-select label="Tipo Comprobante" wire:model="voucher_type">
                                <option value="1">Factura Electrónica</option>
                                <option value="2">Nota de Venta / Ticket</option>
                            </x-w-native-select>

                            <div class="col-span-2">
                                <x-w-native-select label="Metodo de pago" placeholder="Selecciona un metodo de pago" wire:model="payment_method">
                                    <option value="1">Efectivo</option>
                                    <option value="2">Tarjeta</option>
                                    <option value="3">Transferencia</option>
                                    <option value="4">Paypal</option>
                                </x-w-native-select>
                            </div>
                        </div>

                        <!-- Tabla del Carrito -->
                        <div
                            class="border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden flex flex-col h-[380px] bg-white dark:bg-gray-800 shadow-inner">
                            <div
                                class="grid grid-cols-12 gap-2 p-2 text-[10px] font-bold uppercase border-b bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300">
                                <div class="col-span-6">Descripción del Producto</div>
                                <div class="col-span-2 text-center">Cant.</div>
                                <div class="col-span-3 text-right">Subtotal</div>
                                <div class="col-span-1"></div>
                            </div>

                            <div class="overflow-y-auto flex-1 p-2 space-y-2 custom-scrollbar">
                                <template x-for="(product, index) in products" :key="index">
                                    <div
                                        class="grid grid-cols-12 gap-2 items-center bg-white dark:bg-gray-900 p-2 rounded-lg border border-gray-100 dark:border-gray-700 hover:border-emerald-300 transition-colors shadow-sm">

                                        <div class="col-span-6 flex flex-col">
                                            <span
                                                class="text-xs font-bold text-gray-800 dark:text-gray-200 leading-tight"
                                                x-text="product.name"></span>
                                            <span class="text-[9px] font-mono text-emerald-600"
                                                x-text="product.sku"></span>
                                        </div>

                                        <div class="col-span-2">
                                            <input type="number" x-model.number="product.quantity" min="1"
                                                class="w-full text-center text-xs border-gray-300 dark:bg-gray-800 dark:border-gray-600 rounded p-1 focus:ring-emerald-500" />
                                        </div>

                                        <div class="col-span-3 text-right">
                                            <div class="text-xs font-bold text-gray-900 dark:text-gray-100">
                                                $<span
                                                    x-text="((parseFloat(product.quantity) || 0) * (parseFloat(product.price) || 0)).toFixed(2)"></span>
                                            </div>
                                            <div class="text-[9px] text-gray-400">
                                                u: $<span x-text="parseFloat(product.price).toFixed(2)"></span>
                                            </div>
                                        </div>

                                        <div class="col-span-1 flex justify-center">
                                            <button type="button" @click="removeProduct(index)"
                                                class="p-1 text-gray-400 hover:text-red-500 hover:bg-red-50 rounded-full transition-all">
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

                                <template x-if="products.length === 0">
                                    <div
                                        class="h-full flex flex-col items-center justify-center text-gray-400 opacity-40">
                                        <svg class="w-12 h-12 mb-2" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z">
                                            </path>
                                        </svg>
                                        <p class="text-xs italic">El carrito está vacío</p>
                                    </div>
                                </template>
                            </div>
                        </div>

                        <!-- Footer de Venta -->
                        <div class="p-4 bg-emerald-900 text-white rounded-xl space-y-3 shadow-lg">
                            <div class="flex justify-between items-center">
                                <div class="flex flex-col">
                                    <span class="text-[10px] uppercase font-bold text-emerald-300 tracking-wider">Total
                                        a Cobrar</span>
                                    <span class="text-xs text-emerald-400">Incluye impuestos (IGV/IVA)</span>
                                </div>
                                <div class="text-right">
                                    <span class="text-4xl font-black">
                                        $<span x-text="total.toFixed(2)"></span>
                                    </span>
                                    <span class="text-[10px] block font-bold opacity-70">MONEDA: MXN</span>
                                </div>
                            </div>

                            <x-w-button type="submit" spinner="save"
                                class="w-full !bg-white !text-emerald-900 hover:!bg-emerald-50 font-black text-lg py-4 shadow-xl border-none">
                                <div class="flex items-center justify-center gap-2">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z">
                                        </path>
                                    </svg>
                                    PROCESAR PAGO
                                </div>
                            </x-w-button>
                        </div>

                        <x-input-error for="products" />
                        <x-input-error for="total" />

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

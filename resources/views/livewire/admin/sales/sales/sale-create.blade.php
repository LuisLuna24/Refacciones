<div x-data="{
    products: @entangle('products').live,
    total: @entangle('total'),
    
    removeProduct(index) {
        this.products.splice(index, 1);
    },
    increment(index) {
        let currentQty = parseFloat(this.products[index].quantity) || 0;
        this.products[index].quantity = currentQty + 1;
    },
    decrement(index) {
        let currentQty = parseFloat(this.products[index].quantity) || 0;
        if (currentQty > 1) {
            this.products[index].quantity = currentQty - 1;
        } else {
            this.removeProduct(index);
        }
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
            <x-w-card shadow="lg" class="h-full flex flex-col">
                <div class="flex flex-col md:flex-row justify-between items-center mb-4 border-b pb-4 dark:border-gray-700 gap-4">
                    <div class="w-full md:w-auto">
                        <h2 class="text-xl font-bold text-gray-800 dark:text-gray-200">Catálogo</h2>
                        <p class="text-xs text-gray-500">Selecciona productos para agregar</p>
                    </div>

                    <div class="flex flex-1 w-full max-w-2xl gap-2">
                        <div class="w-1/3">
                            <x-w-select placeholder="Categoría" wire:model.live="category_id" 
                                :async-data="['api' => route('api.categories.index'), 'method' => 'GET',]"
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

                <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-4 flex-1 overflow-y-auto pr-2 custom-scrollbar min-h-[600px]">
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
                            {{ !$hasStock || !$hasWarehouse ? 'opacity-60 cursor-not-allowed bg-gray-50 dark:bg-gray-900' : 'cursor-pointer hover:shadow-lg hover:border-emerald-400' }}
                            {{ $isAdded ? 'ring-2 ring-emerald-500 border-emerald-500 bg-emerald-50/20' : 'border-gray-200 dark:border-gray-700' }}">

                            @if ($isAdded)
                                <div class="absolute -top-2 -right-2 z-20">
                                    <span class="flex h-6 w-6 items-center justify-center rounded-full bg-emerald-500 text-white shadow-md ring-2 ring-white dark:ring-gray-800">
                                        <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    </span>
                                </div>
                            @endif

                            <div>
                                <div class="flex justify-between items-start mb-2">
                                    <span class="text-[12px] font-bold text-emerald-700 bg-emerald-50 dark:bg-emerald-900/40 dark:text-emerald-400 px-2 py-0.5 rounded-md">
                                        ${{ number_format($item->price, 2) }}
                                    </span>

                                    @if (!$hasWarehouse)
                                        <span class="text-[10px] text-orange-500 font-bold bg-orange-50 px-1.5 rounded">⚠️ Almacén</span>
                                    @elseif($hasStock)
                                        <span class="text-[10px] font-bold px-1.5 rounded {{ $lowStock ? 'text-yellow-700 bg-yellow-50' : 'text-emerald-600 bg-emerald-50' }}">
                                            {{ $stock }} disp.
                                        </span>
                                    @else
                                        <span class="text-[10px] font-bold text-red-600 bg-red-50 px-1.5 rounded">🚫 Agotado</span>
                                    @endif
                                </div>

                                <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-200 line-clamp-2 leading-tight mt-1">
                                    {{ $item->name }}
                                </h3>
                                <p class="text-[10px] text-gray-400 mt-1 font-mono">{{ $item->sku ?? 'S/SKU' }}</p>
                            </div>

                            <div class="mt-3 flex justify-end">
                                <button type="button" class="text-emerald-600 bg-emerald-50 dark:bg-emerald-900/30 p-1.5 rounded-full group-hover:bg-emerald-500 group-hover:text-white transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full flex flex-col items-center justify-center py-20 text-gray-400">
                            <svg class="w-16 h-16 mb-4 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                            </svg>
                            <p class="font-medium text-lg">No se encontraron productos</p>
                        </div>
                    @endforelse
                </div>

                <div class="mt-4 border-t pt-4 dark:border-gray-700">
                    {{ $catalog->links() }}
                </div>
            </x-w-card>
        </div>

        <div class="lg:w-2/5 space-y-4">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-xl border-t-4 border-emerald-500 flex flex-col h-full">

                <div class="p-3 border-b dark:border-gray-700 flex justify-between items-center bg-gray-50/50 dark:bg-gray-900/30">
                    <div class="flex items-center gap-2">
                        <div class="p-1.5 bg-emerald-100 dark:bg-emerald-900/40 rounded-lg text-emerald-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </div>
                        <h2 class="text-base font-bold text-gray-800 dark:text-emerald-400">Carrito actual</h2>
                    </div>
                    <div class="text-right">
                        <span class="text-[9px] text-gray-500 uppercase block font-bold mb-0.5">Nota / Ticket</span>
                        <span class="text-xs font-mono font-black text-emerald-700 bg-emerald-100 px-2 py-0.5 rounded-md">
                            {{ $serie }}-{{ $correlative }}
                        </span>
                    </div>
                </div>

                <form wire:submit.prevent="save" class="p-4 flex flex-col flex-1 gap-4">

                    <div class="bg-emerald-50/30 dark:bg-gray-900/50 p-3 rounded-lg border border-emerald-100 dark:border-gray-700 grid grid-cols-2 gap-x-3 gap-y-2">
                        <div class="col-span-2">
                            <x-w-select label="Cliente" placeholder="Público en General" wire:model.live="customer_id" 
                                :async-data="['api' => route('api.customers.index'), 'method' => 'GET',]" option-label="name" option-value="id" />
                        </div>
                        <x-w-select label="Almacén" wire:model.live="warehouse_id" 
                            :async-data="['api' => route('api.warehouses.index'), 'method' => 'GET',]" option-label="name" option-value="id" :clearable="false" />
                        
                        <x-w-native-select label="Pago" wire:model="payment_method">
                            <option value="1">Efectivo</option>
                            <option value="2">Tarjeta</option>
                            <option value="3">Transf.</option>
                        </x-w-native-select>
                    </div>

                    <div class="border border-gray-200 dark:border-gray-700 rounded-lg flex flex-col flex-1 bg-white dark:bg-gray-800 shadow-inner min-h-[400px] max-h-[500px]">
                        <div class="grid grid-cols-12 gap-1 p-2 text-[10px] font-bold uppercase border-b bg-gray-50 dark:bg-gray-700/50 text-gray-500 dark:text-gray-400">
                            <div class="col-span-5 pl-1">Producto</div>
                            <div class="col-span-3 text-center">Cant.</div>
                            <div class="col-span-3 text-right">Subt.</div>
                            <div class="col-span-1"></div>
                        </div>

                        <div class="overflow-y-auto flex-1 p-1.5 space-y-1.5 custom-scrollbar">
                            <template x-for="(product, index) in products" :key="index">
                                <div class="grid grid-cols-12 gap-2 items-center bg-white dark:bg-gray-900 p-2 rounded border border-gray-100 dark:border-gray-700/60 hover:shadow-sm transition-all group">

                                    <div class="col-span-5 flex flex-col overflow-hidden">
                                        <span class="text-[11px] font-bold text-gray-800 dark:text-gray-200 leading-tight truncate" x-text="product.name" :title="product.name"></span>
                                        <div class="flex items-center gap-1 mt-0.5">
                                            <span class="text-[9px] text-gray-400" x-text="'$' + parseFloat(product.price).toFixed(2)"></span>
                                        </div>
                                    </div>

                                    <div class="col-span-3">
                                        <div class="flex items-center justify-between bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-600 rounded-md overflow-hidden h-7">
                                            <button type="button" @click="decrement(index)" class="w-6 h-full flex items-center justify-center text-gray-500 hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors active:bg-gray-300">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M20 12H4"></path></svg>
                                            </button>
                                            
                                            <input type="number" x-model.number="product.quantity" min="1"
                                                class="w-full text-center text-[11px] font-bold bg-transparent border-none p-0 focus:ring-0 [-moz-appearance:_textfield] [&::-webkit-outer-spin-button]:m-0 [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:m-0 [&::-webkit-inner-spin-button]:appearance-none" />
                                            
                                            <button type="button" @click="increment(index)" class="w-6 h-full flex items-center justify-center text-emerald-600 hover:bg-emerald-100 dark:hover:bg-emerald-900/50 transition-colors active:bg-emerald-200">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"></path></svg>
                                            </button>
                                        </div>
                                    </div>

                                    <div class="col-span-3 text-right">
                                        <div class="text-xs font-black text-gray-800 dark:text-gray-100">
                                            $<span x-text="((parseFloat(product.quantity) || 0) * (parseFloat(product.price) || 0)).toFixed(2)"></span>
                                        </div>
                                    </div>

                                    <div class="col-span-1 flex justify-end">
                                        <button type="button" @click="removeProduct(index)" class="p-1.5 text-gray-300 hover:text-red-500 hover:bg-red-50 dark:hover:bg-red-900/30 rounded-md transition-all">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </template>

                            <template x-if="products.length === 0">
                                <div class="h-full min-h-[200px] flex flex-col items-center justify-center text-gray-400 opacity-60">
                                    <svg class="w-10 h-10 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                                    </svg>
                                    <p class="text-xs font-medium">Agrega productos al carrito</p>
                                </div>
                            </template>
                        </div>
                    </div>

                    <div class="p-4 bg-emerald-900 text-white rounded-xl shadow-lg mt-auto">
                        <div class="flex justify-between items-end mb-4">
                            <div class="flex flex-col">
                                <span class="text-[10px] uppercase font-bold text-emerald-300 tracking-widest">Total a Pagar</span>
                                <span class="text-[10px] text-emerald-400 mt-0.5">Impuestos incluidos</span>
                            </div>
                            <div class="text-right">
                                <span class="text-4xl font-black tracking-tight">
                                    $<span x-text="total.toFixed(2)"></span>
                                </span>
                            </div>
                        </div>

                        <x-w-button type="submit" spinner="save"
                            class="w-full !bg-emerald-500 hover:!bg-emerald-400 !text-white font-bold text-lg py-3 rounded-lg shadow-xl border-none transition-colors">
                            <div class="flex items-center justify-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                CONFIRMAR VENTA
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
<div x-data="{
    products: @entangle('products').live,
    total: @entangle('total'),
    type: @entangle('type').live,
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
    get isEntry() { return this.type == 1; }
}" class="max-w-[1800px] mx-auto p-2">

    <div class="flex flex-col lg:flex-row gap-6">

        {{-- ===== CATÁLOGO ===== --}}
        <div class="lg:w-3/5 space-y-4 relative">

            @if (!$warehouse_id)
                <div
                    class="absolute inset-0 z-20 bg-gray-100/80 dark:bg-gray-900/80 backdrop-blur-sm rounded-xl flex flex-col items-center justify-center text-center p-6 border-2 border-dashed border-gray-300 dark:border-gray-700">
                    <div class="bg-white dark:bg-gray-800 p-4 rounded-full shadow-lg mb-3">
                        <svg class="w-8 h-8 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-700 dark:text-gray-200">Selecciona un Almacén</h3>
                    <p class="text-sm text-gray-500">Es necesario para calcular el stock actual.</p>
                </div>
            @endif

            <x-w-card shadow="lg">
                <div class="flex flex-col gap-4 justify-between items-center mb-4 border-b pb-4 dark:border-gray-700 md:flex-row md:items-center">
                    <div>
                        <h2 class="text-xl font-bold text-gray-800 dark:text-gray-200"
                            x-text="isEntry ? 'Productos para Ingresar' : 'Productos para Retirar'"></h2>
                        <p class="text-xs text-gray-500">Toca una tarjeta para agregar · vuelve a tocarla para sumar 1 más</p>
                    </div>

                    <div class="relative w-full max-w-xs md:max-w-md">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                        <input wire:model.live.debounce.300ms="search" type="text" placeholder="Buscar producto..."
                            class="pl-10 w-full border-gray-300 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-200 rounded-md shadow-sm focus:ring-2 transition-colors"
                            :class="isEntry ? 'focus:ring-emerald-500 focus:border-emerald-500' : 'focus:ring-rose-500 focus:border-rose-500'"
                            {{ !$warehouse_id ? 'disabled' : '' }}>
                    </div>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-3 h-[600px] content-start overflow-y-auto pr-2 custom-scrollbar">
                    @forelse($catalog as $item)
                        @php
                            $stock = intval($item->stock ?? 0);
                            $isEntryType = $type == 1;
                            $added = collect($products)->firstWhere('id', $item->id);
                            $isAdded = (bool) $added;
                            $addedQty = $isAdded ? round((float) $added['quantity'], 2) : 0;
                            $isDisabled = !$isEntryType && $stock <= 0 && !$isAdded;
                        @endphp

                        <div wire:key="prod-{{ $item->id }}"
                            @if (!$isDisabled)
                                wire:click="{{ $isAdded ? 'incrementFromCard' : 'addFromCard' }}({{ $item->id }})"
                                @keydown.enter="$wire.{{ $isAdded ? 'incrementFromCard' : 'addFromCard' }}({{ $item->id }})"
                                role="button" tabindex="0"
                            @endif
                            title="{{ $item->name }}"
                            class="group relative flex flex-col justify-between h-full rounded-xl border bg-white dark:bg-gray-800 p-3 shadow-sm transition-all
                            {{ $isDisabled
                                ? 'cursor-not-allowed opacity-60 border-gray-200 dark:border-gray-700'
                                : ($isAdded
                                    ? ($isEntryType
                                        ? 'cursor-pointer ring-2 ring-emerald-500 border-emerald-500 bg-emerald-50/40 dark:bg-emerald-900/10 hover:shadow-md'
                                        : 'cursor-pointer ring-2 ring-rose-500 border-rose-500 bg-rose-50/40 dark:bg-rose-900/10 hover:shadow-md')
                                    : ($isEntryType
                                        ? 'cursor-pointer border-gray-200 dark:border-gray-700 focus:outline-none focus:ring-2 focus:ring-emerald-400 hover:-translate-y-0.5 hover:shadow-md hover:border-emerald-400'
                                        : 'cursor-pointer border-gray-200 dark:border-gray-700 focus:outline-none focus:ring-2 focus:ring-rose-400 hover:-translate-y-0.5 hover:shadow-md hover:border-rose-400')) }}">

                            @if ($isAdded)
                                <div class="absolute -top-2 -right-2 z-20" title="{{ $addedQty }} en la lista">
                                    <span
                                        class="inline-flex h-6 min-w-[1.5rem] items-center justify-center rounded-full px-1.5 text-[11px] font-black text-white shadow-md ring-2 ring-white dark:ring-gray-800 {{ $isEntryType ? 'bg-emerald-500' : 'bg-rose-500' }}">
                                        +{{ $addedQty }}
                                    </span>
                                </div>
                            @endif

                            <div>
                                <div class="flex items-start justify-between gap-2 mb-2">
                                    <span class="rounded px-1.5 py-0.5 font-mono text-[10px] {{ $isAdded ? ($isEntryType ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-400' : 'bg-rose-100 text-rose-700 dark:bg-rose-900/40 dark:text-rose-400') : 'bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-300' }}">
                                        {{ $item->sku ?? '---' }}
                                    </span>

                                    <span class="rounded px-1.5 py-0.5 text-[10px] font-bold flex items-center gap-1 {{ $isEntryType ? 'text-emerald-700 bg-emerald-50 dark:text-emerald-400 dark:bg-emerald-900/40' : ($stock > 0 ? 'text-rose-700 bg-rose-50 dark:text-rose-400 dark:bg-rose-900/40' : 'text-gray-500 bg-gray-100 dark:bg-gray-700 dark:text-gray-300') }}">
                                        Stock: {{ $stock }}
                                    </span>
                                </div>

                                <h3 title="{{ $item->name }}"
                                    class="text-sm font-semibold text-gray-700 dark:text-gray-200 leading-snug break-words line-clamp-3">
                                    {{ $item->name }}
                                </h3>

                                <p class="mt-1 text-[10px] text-gray-400">Costo: ${{ number_format($item->cost ?? 0, 2) }}</p>
                            </div>

                            @if ($isDisabled)
                                <div class="absolute inset-0 flex items-center justify-center z-10">
                                    <span class="bg-gray-100 text-gray-500 text-xs font-bold px-2 py-1 rounded border border-gray-300 shadow-sm transform -rotate-12">SIN STOCK</span>
                                </div>
                            @endif

                            <div class="mt-2 flex items-center justify-end">
                                @if ($isAdded)
                                    <span class="text-[11px] font-bold {{ $isEntryType ? 'text-emerald-600 dark:text-emerald-400' : 'text-rose-600 dark:text-rose-400' }}">{{ $addedQty }} en lista</span>
                                    <span class="ml-auto inline-flex h-6 w-6 items-center justify-center rounded-full text-white shadow-sm {{ $isEntryType ? 'bg-emerald-500' : 'bg-rose-500' }}" title="Aumentar cantidad">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path>
                                        </svg>
                                    </span>
                                @else
                                    <span class="inline-flex h-6 w-6 items-center justify-center rounded-full transition-colors
                                        {{ $isEntryType
                                            ? 'bg-emerald-100 text-emerald-600 dark:bg-emerald-900/40 dark:text-emerald-400 group-hover:bg-emerald-500 group-hover:text-white'
                                            : 'bg-rose-100 text-rose-600 dark:bg-rose-900/40 dark:text-rose-400 group-hover:bg-rose-500 group-hover:text-white' }}"
                                        title="{{ $isEntryType ? 'Agregar al ingreso' : 'Agregar a la salida' }}">
                                        <template x-if="isEntry">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                            </svg>
                                        </template>
                                        <template x-if="!isEntry">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                                            </svg>
                                        </template>
                                    </span>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full flex flex-col items-center justify-center py-10 text-gray-400 border-2 border-dashed border-gray-200 dark:border-gray-700 rounded-xl gap-2">
                            <svg class="w-10 h-10 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            <p class="text-sm font-medium">Sin resultados.</p>
                        </div>
                    @endforelse
                </div>

                <div class="mt-3">
                    {{ $catalog->links() }}
                </div>
            </x-w-card>
        </div>

        {{-- ===== REGISTRO DE MOVIMIENTO ===== --}}
        <div class="lg:w-2/5 space-y-4">

            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border-t-4 transition-colors duration-300"
                :class="isEntry ? 'border-emerald-500' : 'border-rose-500'">

                <div
                    class="p-4 border-b dark:border-gray-700 flex justify-between items-center bg-gray-50 dark:bg-gray-900/50 rounded-t-xl">
                    <div class="flex items-center gap-2">
                        <h2 class="text-lg font-bold flex items-center gap-2"
                            :class="isEntry ? 'text-gray-800 dark:text-emerald-400' : 'text-gray-800 dark:text-rose-400'">
                            <template x-if="isEntry">
                                <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12"></path>
                                </svg>
                            </template>
                            <template x-if="!isEntry">
                                <svg class="w-5 h-5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 13l-5 5m0 0l-5-5m5 5V6"></path>
                                </svg>
                            </template>
                            <span x-text="isEntry ? 'Registrar Ingreso' : 'Registrar Salida'"></span>
                        </h2>
                        <span class="text-[10px] text-gray-400 uppercase block font-bold">Documento N°</span>
                    </div>
                    <span class="text-xs font-mono text-gray-400" x-text="'{{ $serie }}-' + '{{ $correlative }}'"></span>
                </div>

                <div class="p-4">
                    <form wire:submit.prevent="save" class="space-y-4">

                        <div class="grid grid-cols-2 gap-3">
                            <div class="col-span-2">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-400 mb-1">Tipo de Movimiento</label>
                                <div class="grid grid-cols-2 gap-2 bg-gray-100 dark:bg-gray-900 p-1 rounded-lg">
                                    <button type="button" @click="$wire.set('type', 1)"
                                        class="px-4 py-2 text-sm font-bold rounded-md transition-all text-center flex items-center justify-center gap-2"
                                        :class="isEntry ? 'bg-white text-emerald-600 shadow-sm' : 'text-gray-500 hover:text-gray-700'">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                        </svg>
                                        Ingreso
                                    </button>
                                    <button type="button" @click="$wire.set('type', 2)"
                                        class="px-4 py-2 text-sm font-bold rounded-md transition-all text-center flex items-center justify-center gap-2"
                                        :class="!isEntry ? 'bg-white text-rose-600 shadow-sm' : 'text-gray-500 hover:text-gray-700'">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                                        </svg>
                                        Salida
                                    </button>
                                </div>
                            </div>

                            <x-w-select label="Almacén *" wire:model.live="warehouse_id" :async-data="['api' => route('api.warehouses.index'), 'method' => 'GET']"
                                option-label="name" option-value="id" :disabled="count($products) > 0" />

                            <x-w-select label="Razón" placeholder="..." wire:model="reason_id" :async-data="[
                                'api' => route('api.reasons.index'),
                                'method' => 'GET',
                                'params' => ['type' => $type],
                            ]"
                                option-label="name" option-value="id" />

                            <x-w-input type="date" wire:model="date" label="Fecha" class="col-span-2" />
                        </div>

                        <div class="border rounded-lg overflow-hidden flex flex-col h-[350px] transition-colors bg-white dark:bg-gray-800 shadow-inner"
                            :class="isEntry ? 'border-emerald-300 dark:border-emerald-700' : 'border-rose-300 dark:border-rose-700'">

                            <div class="grid grid-cols-12 gap-2 p-2 text-xs font-bold uppercase border-b"
                                :class="isEntry ? 'bg-emerald-50 text-emerald-800 border-emerald-100 dark:bg-emerald-900/20 dark:text-emerald-300' : 'bg-rose-50 text-rose-800 border-rose-100 dark:bg-rose-900/20 dark:text-rose-300'">
                                <div class="col-span-5 pl-1">Producto</div>
                                <div class="col-span-2 text-center">Cant.</div>
                                <div class="col-span-4 text-right">Costo</div>
                                <div class="col-span-1"></div>
                            </div>

                            <div class="overflow-y-auto flex-1 p-2 space-y-2">
                                <template x-for="(product, index) in products" :key="index">
                                    <div class="grid grid-cols-12 gap-2 items-center bg-white dark:bg-gray-900 p-2 rounded-lg shadow-sm border border-gray-100 dark:border-gray-700">

                                        <div class="col-span-5 flex flex-col min-w-0">
                                            <div class="text-xs font-bold text-gray-800 dark:text-gray-200 leading-snug break-words line-clamp-2" :title="product.name" x-text="product.name"></div>
                                            <div class="text-[9px] text-gray-400 truncate" x-text="product.sku"></div>
                                            <template x-if="!isEntry">
                                                <div class="text-[9px] text-rose-500 font-bold">Max: <span x-text="product.stock_actual"></span></div>
                                            </template>
                                        </div>

                                        <div class="col-span-2">
                                            <div class="flex items-center justify-between bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-600 rounded-md overflow-hidden h-7">
                                                <button type="button" @click="product.quantity = Math.max(1, (parseFloat(product.quantity) || 1) - 1)" class="w-6 h-full flex items-center justify-center text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M20 12H4"></path></svg>
                                                </button>
                                                <input type="number" x-model.number="product.quantity" min="1"
                                                    x-bind:max="!isEntry ? product.stock_actual : ''"
                                                    class="w-full text-center text-[11px] font-bold bg-transparent border-none p-0 focus:ring-0 [-moz-appearance:_textfield] [&::-webkit-outer-spin-button]:m-0 [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:m-0 [&::-webkit-inner-spin-button]:appearance-none" />
                                                <button type="button" x-on:click="product.quantity = Math.min(!isEntry ? (parseFloat(product.stock_actual) || 1) : 99999, (parseFloat(product.quantity) || 1) + 1)" class="w-6 h-full flex items-center justify-center text-gray-600 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"></path></svg>
                                                </button>
                                            </div>
                                        </div>

                                        <div class="col-span-4 text-right flex flex-col">
                                            <input type="number" step="0.01" x-model.number="product.price"
                                                class="w-full text-right text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white rounded-md shadow-sm focus:ring-2 p-1"
                                                :class="isEntry ? 'focus:ring-emerald-500 focus:border-emerald-500' : 'focus:ring-rose-500 focus:border-rose-500'">
                                            <div class="text-[9px] text-gray-400 mt-1">Sub: $<span x-text="((parseFloat(product.quantity) || 0) * (parseFloat(product.price) || 0)).toFixed(2)"></span></div>
                                        </div>

                                        <div class="col-span-1 flex justify-center">
                                            <button type="button" @click="removeProduct(index)"
                                                class="p-1 text-gray-400 hover:text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-full transition-colors">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                </template>

                                <template x-if="products.length === 0">
                                    <div class="h-full flex flex-col items-center justify-center text-gray-400 opacity-60 gap-2">
                                        <svg class="w-10 h-10 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                                        </svg>
                                        <p class="text-xs font-medium">Sin items</p>
                                    </div>
                                </template>
                            </div>
                        </div>

                        <div class="p-4 rounded-xl space-y-3 border transition-colors bg-emerald-50/30 dark:bg-gray-900/40"
                            :class="isEntry ? 'border-emerald-200 dark:border-emerald-800' : 'border-rose-200 dark:border-rose-800'">

                            <x-w-textarea label="Observaciones" wire:model="observation" placeholder="..." rows="1" />

                            <div class="flex justify-between items-end pt-2 border-t"
                                :class="isEntry ? 'border-emerald-200 dark:border-emerald-700' : 'border-rose-200 dark:border-rose-700'">
                                <span class="text-xs font-bold uppercase"
                                    :class="isEntry ? 'text-emerald-800 dark:text-emerald-300' : 'text-rose-800 dark:text-rose-300'">Costo Total</span>
                                <span class="text-3xl font-black"
                                    :class="isEntry ? 'text-emerald-600 dark:text-emerald-400' : 'text-rose-600 dark:text-rose-400'">
                                    $<span x-text="total.toFixed(2)"></span>
                                </span>
                            </div>
                        </div>

                        <x-w-button type="submit" spinner="save" xl
                            class="w-full shadow-md text-white transition-colors"
                            x-bind:class="isEntry ? 'bg-emerald-600 hover:bg-emerald-700' : 'bg-rose-600 hover:bg-rose-700'">
                            <span x-text="isEntry ? 'CONFIRMAR INGRESO' : 'CONFIRMAR SALIDA'"></span>
                        </x-w-button>

                        <x-input-error for="products" />
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="max-w-6xl mx-auto bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md transition-colors duration-200">

    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800 dark:text-white">
            {{ $status == 3 ? 'Detalle de Nota de Entrega' : 'Nueva Nota de Entrega' }}
        </h2>

        {{-- Banner visual si está entregado --}}
        @if ($status == 3)
            <span
                class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-400">
                <svg class="mr-1.5 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Nota Entregada (Solo lectura)
            </span>
        @endif
    </div>

    <form wire:submit="save">

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8 pb-6 border-b border-gray-200 dark:border-gray-700">

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Cliente (Opcional)</label>
                <select wire:model="customer_id" {{ $status == 3 ? 'disabled' : '' }}
                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-60 disabled:bg-gray-100 dark:disabled:bg-gray-800 disabled:cursor-not-allowed transition-colors">
                    <option value="">-- Sin cliente (Público general) --</option>
                    @foreach ($customers as $customer)
                        <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                    @endforeach
                </select>
                <x-input-error for="customer_id" />
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Almacén *</label>
                <select wire:model="warehouse_id" required {{ $status == 3 ? 'disabled' : '' }}
                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-60 disabled:bg-gray-100 dark:disabled:bg-gray-800 disabled:cursor-not-allowed transition-colors">
                    <option value="">-- Selecciona un almacén --</option>
                    @foreach ($warehouses as $warehouse)
                        <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                    @endforeach
                </select>
                <x-input-error for="warehouse_id" />
            </div>

            <div class="flex space-x-2">
                <div class="w-1/3">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Serie *</label>
                    <input type="text" wire:model="serie" required {{ $status == 3 ? 'disabled' : '' }}
                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm disabled:opacity-60 disabled:bg-gray-100 dark:disabled:bg-gray-800 disabled:cursor-not-allowed transition-colors">
                    <x-input-error for="serie" />
                </div>
                <div class="w-2/3">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Correlativo *</label>
                    <input type="text" wire:model="correlative" required {{ $status == 3 ? 'disabled' : '' }}
                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm disabled:opacity-60 disabled:bg-gray-100 dark:disabled:bg-gray-800 disabled:cursor-not-allowed transition-colors">
                    <x-input-error for="correlative" />
                </div>
            </div>

        </div>

        <div class="mb-6">
            <h3 class="text-lg font-semibold mb-4 text-gray-800 dark:text-white">Productos / Servicios</h3>

            <x-input-error for="items" />

            <div class="space-y-4">

                @foreach ($items as $index => $item)
                    <div
                        class="flex items-center space-x-4 bg-gray-50 dark:bg-gray-900 p-4 rounded-md border border-gray-200 dark:border-gray-700 transition-colors">

                        <div class="w-1/4">
                            <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Producto
                                Catálogo</label>
                            <select wire:model="items.{{ $index }}.product_id"
                                {{ $status == 3 ? 'disabled' : '' }}
                                class="block w-full text-sm rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white shadow-sm disabled:opacity-60 disabled:cursor-not-allowed transition-colors">
                                <option value="">-- Personalizado --</option>
                                @foreach ($catalogProducts as $product)
                                    <option value="{{ $product->id }}">{{ $product->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="w-2/4">
                            <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Descripción
                                del trabajo *</label>
                            <input type="text" wire:model="items.{{ $index }}.description" required
                                {{ $status == 3 ? 'disabled' : '' }} placeholder="Ej: Rotulación a medida..."
                                class="block w-full text-sm rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 shadow-sm disabled:opacity-60 disabled:cursor-not-allowed transition-colors">
                        </div>

                        <div class="w-1/6">
                            <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Cant.</label>
                            <input type="number" step="0.01" wire:model.live="items.{{ $index }}.quantity"
                                required {{ $status == 3 ? 'disabled' : '' }}
                                class="block w-full text-sm rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white shadow-sm disabled:opacity-60 disabled:cursor-not-allowed transition-colors">
                        </div>

                        <div class="w-1/6">
                            <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Precio
                                Unit.</label>
                            <input type="number" step="0.01" wire:model.live="items.{{ $index }}.price"
                                required {{ $status == 3 ? 'disabled' : '' }}
                                class="block w-full text-sm rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white shadow-sm disabled:opacity-60 disabled:cursor-not-allowed transition-colors">
                        </div>

                        {{-- Solo mostramos el botón de eliminar si NO está entregado --}}
                        @if ($status != 3)
                            <div class="w-auto pt-5">
                                <button type="button" wire:click="removeItem({{ $index }})"
                                    class="text-red-500 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300 focus:outline-none transition-colors">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                        </path>
                                    </svg>
                                </button>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>

            {{-- Solo mostramos el botón de agregar si NO está entregado --}}
            @if ($status != 3)
                <button type="button" wire:click="addItem"
                    class="mt-4 inline-flex items-center px-4 py-2 bg-blue-50 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400 text-sm font-medium rounded-md hover:bg-blue-100 dark:hover:bg-blue-900/50 transition-colors">
                    + Agregar otra línea
                </button>
            @endif
        </div>

        <div
            class="flex flex-col md:flex-row justify-between items-start border-t border-gray-200 dark:border-gray-700 pt-6 mt-6 gap-8">

            <div class="w-full md:w-1/2">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Observaciones / Notas
                    adicionales</label>
                <textarea wire:model="observation" rows="4" {{ $status == 3 ? 'disabled' : '' }}
                    class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-60 disabled:bg-gray-100 dark:disabled:bg-gray-800 disabled:cursor-not-allowed transition-colors"></textarea>
            </div>

            <div
                class="w-full md:w-1/3 bg-gray-50 dark:bg-gray-900 p-5 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm transition-colors">

                <div class="flex justify-between items-center mb-3">
                    <span class="text-gray-600 dark:text-gray-400 font-medium">Total general:</span>
                    <span class="text-xl font-bold text-gray-800 dark:text-white">
                        ${{ number_format($this->getTotal(), 2) }}
                    </span>
                </div>

                <div class="flex justify-between items-center mb-4">
                    <span class="text-gray-600 dark:text-gray-400 font-medium">Abono / Anticipo:</span>
                    <div class="w-1/2 relative">
                        <span class="absolute left-3 top-2 text-gray-500 dark:text-gray-400">$</span>
                        <input type="number" step="0.01" wire:model.live="installment" min="0"
                            {{ $status == 3 ? 'disabled' : '' }}
                            class="pl-7 block w-full text-right text-sm rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white shadow-sm focus:ring-blue-500 focus:border-blue-500 disabled:opacity-60 disabled:cursor-not-allowed transition-colors">
                    </div>
                </div>

                <div class="flex justify-between items-center border-t border-gray-300 dark:border-gray-600 pt-3">
                    <span class="text-gray-800 dark:text-white font-bold">Saldo Pendiente:</span>
                    <span
                        class="text-2xl font-black {{ $this->getBalance() > 0 ? 'text-red-500 dark:text-red-400' : 'text-green-500 dark:text-green-400' }}">
                        ${{ number_format($this->getBalance(), 2) }}
                    </span>
                </div>

            </div>
        </div>

        {{-- Solo mostramos el botón de guardar si NO está entregado --}}
        @if ($status != 3)
            <div class="mt-8 text-right">
                <button type="submit"
                    class="bg-gray-900 dark:bg-blue-600 text-white px-8 py-3 rounded-md hover:bg-gray-800 dark:hover:bg-blue-500 font-bold shadow-md transition-colors text-lg">
                    Guardar Nota de Entrega
                </button>
            </div>
        @endif

    </form>
</div>

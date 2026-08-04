<div class="max-w-[1400px] mx-auto p-2 space-y-4">
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border-t-4 border-emerald-500 overflow-hidden">

        <!-- Cabecera Principal -->
        <div
            class="p-4 border-b dark:border-gray-700 flex flex-col md:flex-row justify-between items-center bg-gray-50 dark:bg-gray-900/50 gap-4">
            <div class="flex items-center gap-3">
                <div class="p-2 bg-emerald-100 dark:bg-emerald-900/40 rounded-lg text-emerald-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0">
                        </path>
                    </svg>
                </div>
                <div>
                    <h2 class="text-xl font-bold text-gray-800 dark:text-white uppercase tracking-tight">
                        {{ $status == 3 ? 'Detalle de Nota de Entrega' : 'Nueva Nota de Entrega' }}
                    </h2>
                    @if ($status == 3)
                        <span
                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800 dark:bg-emerald-900/50 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800">
                            ✅ NOTA ENTREGADA (SOLO LECTURA)
                        </span>
                    @endif
                </div>
            </div>

            <!-- Serie y Correlativo -->
            <div
                class="flex flex-col items-end bg-white dark:bg-gray-800 px-4 py-2 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm select-none">
                <span
                    class="text-[10px] text-gray-400 uppercase font-black leading-none mb-1 tracking-tighter">Documento
                    N°</span>
                <span class="font-mono font-bold text-emerald-600 dark:text-emerald-400 text-lg tracking-widest">
                    {{ $serie }}-{{ $correlative }}
                </span>
            </div>
        </div>

        <div class="p-6">
            <form wire:submit="save" class="space-y-8">

                <!-- Sección 1: Datos de Cabecera -->
                <div class="w-full flex flex-col gap-6 items-start">

                    <!-- Selección de Cliente -->
                    <div class="w-full grid grid-cols-1 md:grid-cols-2 gap-3">
                        <div class="space-y-1">
                            <label class="text-xs font-black text-gray-500 uppercase flex items-center gap-1">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z">
                                    </path>
                                </svg>
                                Cliente Registrado
                            </label>
                            <select wire:model.live="customer_id" {{ $status == 3 ? 'disabled' : '' }}
                                class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-emerald-500 focus:border-emerald-500 disabled:opacity-50 transition-all">
                                <option value="">-- Público General / Ocasional --</option>
                                @foreach ($customers as $customer)
                                    <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                                @endforeach
                            </select>
                            <x-input-error for="customer_id" />
                        </div>

                        <!-- Almacén -->
                        <div class="space-y-1">
                            <label class="text-xs font-black text-gray-500 uppercase flex items-center gap-1">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                                    </path>
                                </svg>
                                Almacén de Despacho *
                            </label>
                            <select wire:model="warehouse_id" required {{ $status == 3 ? 'disabled' : '' }}
                                class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-emerald-500 focus:border-emerald-500 disabled:opacity-50 transition-all">
                                <option value="">-- Selecciona origen --</option>
                                @foreach ($warehouses as $warehouse)
                                    <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                                @endforeach
                            </select>
                            <x-input-error for="warehouse_id" />
                        </div>
                    </div>
                    <!-- Panel Cliente Ocasional (si aplica) -->
                    @if (empty($customer_id))
                        <div
                            class="w-full md:col-span-2 lg:col-span-1 bg-emerald-50/50 dark:bg-emerald-900/10 p-4 rounded-xl border border-emerald-100 dark:border-emerald-800/30">
                            <div class="flex items-center gap-2 mb-3">
                                <span
                                    class="p-1 bg-emerald-200 dark:bg-emerald-800 rounded text-emerald-700 dark:text-emerald-300">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path
                                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                                        </path>
                                    </svg>
                                </span>
                                <span class="text-sm font-bold text-emerald-800 dark:text-emerald-300">Datos
                                    Ocasionales</span>
                            </div>
                            <div class="space-y-3">
                                <input type="text" wire:model="guest_name" required
                                    {{ $status == 3 ? 'disabled' : '' }} placeholder="Nombre completo *"
                                    class="w-full text-sm rounded-lg border-emerald-200 dark:bg-gray-800 focus:ring-emerald-500 transition-all">
                                <div class="grid grid-cols-2 gap-2">
                                    <input type="text" wire:model="guest_phone" {{ $status == 3 ? 'disabled' : '' }}
                                        placeholder="Teléfono"
                                        class="w-full text-sm rounded-lg border-emerald-200 dark:bg-gray-800">
                                    <input type="email" wire:model="guest_email" {{ $status == 3 ? 'disabled' : '' }}
                                        placeholder="Correo"
                                        class="w-full text-sm rounded-lg border-emerald-200 dark:bg-gray-800">
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Sección 2: Tabla de Items -->
                <div class="space-y-4">
                    <div class="flex items-center justify-between border-b border-gray-100 dark:border-gray-700 pb-2">
                        <h3 class="text-sm font-black text-gray-400 uppercase tracking-widest flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4">
                                </path>
                            </svg>
                            Detalle de Entrega
                        </h3>
                    </div>

                    <div
                        class="border border-gray-200 dark:border-gray-700 rounded-xl shadow-inner bg-white dark:bg-gray-800">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-900/50">
                                <tr class="text-[10px] font-bold text-gray-500 uppercase">
                                    <th class="px-4 py-3 text-left w-1/5">Producto / Servicio</th>

                                    <th class="px-4 py-3 text-left">Descripción Personalizada</th>

                                    <th class="px-4 py-3 text-center w-24">Cant.</th>
                                    <th class="px-4 py-3 text-right w-32">P. Unit.</th>
                                    <th class="px-4 py-3 text-right w-32">Subtotal</th>

                                    @if ($status != 3)
                                        <th class="px-4 py-3 text-center w-10"></th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                                @foreach ($items as $index => $item)
                                    <tr class="hover:bg-emerald-50/30 dark:hover:bg-emerald-900/5 transition-colors">

                                        <td class="px-4 py-3 max-w-xs">
                                            <x-w-select wire:model.live="items.{{ $index }}.product_id"
                                                placeholder="-- Pedido personalizado --" :async-data="['api' => route('api.productsnotes.index'), 'method' => 'GET',]"
                                                option-label="name" option-value="id" :disabled="$status == 3" />
                                        </td>

                                        <td class="px-4 py-3">
                                            <input type="text" wire:model="items.{{ $index }}.description"
                                                @disabled($status == 3)
                                                placeholder="Ej: Instalación de vinil a medida sobre superficie de aluminio..."
                                                class="w-full text-xs rounded-lg border-gray-200 dark:bg-gray-800 dark:border-gray-600 focus:ring-emerald-500 transition-all disabled:opacity-50 disabled:bg-gray-100 dark:disabled:bg-gray-900 disabled:cursor-not-allowed">
                                        </td>

                                        <td class="px-4 py-3 text-center">
                                            <input type="number" step="0.01"
                                                wire:model.live="items.{{ $index }}.quantity" required
                                                @disabled($status == 3)
                                                class="w-full text-center text-xs font-bold rounded-lg border-gray-200 dark:bg-gray-800 dark:border-gray-600 focus:ring-emerald-500 disabled:opacity-50 disabled:bg-gray-100 dark:disabled:bg-gray-900 disabled:cursor-not-allowed">
                                        </td>

                                        <td class="px-4 py-3">
                                            <div class="relative">
                                                <span class="absolute left-2 top-2 text-gray-400 text-xs">$</span>
                                                <input type="number" step="0.01" min="0"
                                                    wire:model.live="items.{{ $index }}.price" required
                                                    @disabled($status == 3)
                                                    class="w-full text-right text-xs font-bold pl-5 rounded-lg border-gray-200 dark:bg-gray-800 dark:border-gray-600 focus:ring-emerald-500 disabled:opacity-50 disabled:bg-gray-100 dark:disabled:bg-gray-900 disabled:cursor-not-allowed">
                                            </div>
                                        </td>

                                        <td
                                            class="px-4 py-3 text-right font-black text-gray-700 dark:text-gray-200 text-sm whitespace-nowrap">
                                            ${{ number_format(($item['quantity'] ?? 0) * ($item['price'] ?? 0), 2) }}
                                        </td>

                                        @if ($status != 3)
                                            <td class="px-4 py-3 text-center">
                                                <button type="button" wire:click="removeItem({{ $index }})"
                                                    class="p-1.5 text-red-400 hover:text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-all">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                        </path>
                                                    </svg>
                                                </button>
                                            </td>
                                        @endif
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    @if ($status != 3)
                        <button type="button" wire:click="addItem"
                            class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-600 text-white text-xs font-bold rounded-lg hover:bg-emerald-700 shadow-md transition-all uppercase tracking-widest">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                    d="M12 4v16m8-8H4"></path>
                            </svg>
                            Nueva Línea
                        </button>
                    @endif
                </div>

                <!-- Sección 3: Observaciones y Totales -->
                <div
                    class="flex flex-col lg:flex-row justify-between items-start gap-8 bg-gray-50 dark:bg-gray-900/30 p-6 rounded-2xl border border-gray-100 dark:border-gray-700">

                    <div class="w-full lg:w-1/2 space-y-2">
                        <label class="text-xs font-black text-gray-500 uppercase tracking-widest">Observaciones de la
                            Nota</label>
                        <textarea wire:model="observation" rows="4" {{ $status == 3 ? 'disabled' : '' }}
                            placeholder="Instrucciones de entrega, detalles del pago pendiente, etc..."
                            class="w-full rounded-xl border-gray-200 dark:bg-gray-800 dark:text-white focus:ring-emerald-500 transition-all"></textarea>
                    </div>

                    <div class="w-full lg:w-1/3 space-y-4">
                        <div class="space-y-3">
                            <div class="flex justify-between items-center text-sm">
                                <span class="text-gray-500 font-bold uppercase">Total General</span>
                                <span
                                    class="font-black text-gray-800 dark:text-white text-lg">${{ number_format($this->getTotal(), 2) }}</span>
                            </div>

                            <div
                                class="flex justify-between items-center bg-white dark:bg-gray-800 p-3 rounded-xl border border-emerald-100 shadow-sm">
                                <span
                                    class="text-emerald-700 dark:text-emerald-400 font-bold uppercase text-xs">Anticipo
                                    / Abono</span>
                                <div class="w-32 relative">
                                    <span class="absolute left-3 top-1.5 text-emerald-500 font-bold">$</span>
                                    <input type="number" step="0.01" wire:model.live="installment"
                                        min="0" {{ $status == 3 ? 'disabled' : '' }}
                                        class="w-full text-right text-sm font-black text-emerald-600 border-none focus:ring-0 p-1 bg-transparent">
                                </div>
                            </div>

                            <div
                                class="pt-4 border-t-2 border-dashed border-gray-200 dark:border-gray-700 flex justify-between items-center">
                                <div class="flex flex-col">
                                    <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Saldo
                                        Restante</span>
                                    <span
                                        class="text-xs font-bold {{ $this->getBalance() > 0 ? 'text-red-500' : 'text-emerald-600' }}">
                                        {{ $this->getBalance() > 0 ? 'PENDIENTE DE COBRO' : 'TOTALMENTE PAGADO' }}
                                    </span>
                                </div>
                                <span
                                    class="text-4xl font-black {{ $this->getBalance() > 0 ? 'text-red-600' : 'text-emerald-600' }} tracking-tight">
                                    ${{ number_format($this->getBalance(), 2) }}
                                </span>
                            </div>
                        </div>

                        @if ($status != 3)
                            <button type="submit"
                                class="w-full bg-gray-900 dark:bg-emerald-600 text-white px-6 py-4 rounded-xl font-black text-lg hover:bg-black dark:hover:bg-emerald-500 shadow-xl transition-all flex items-center justify-center gap-3">
                                <svg class="w-6 h-6 text-emerald-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4">
                                    </path>
                                </svg>
                                GUARDAR NOTA
                            </button>
                        @endif
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

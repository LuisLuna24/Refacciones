<div>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');

        body {
            font-family: 'Inter', sans-serif;
        }
    </style>

    <section class=" bg-gray-50 dark:bg-gray-900 min-h-screen flex items-center justify-center">

        <div class="max-w-5xl mx-auto px-4 w-full">
            <div class="text-center mb-12">
                <p class="text-sm tracking-widest uppercase mb-3 text-amber-400 font-mono">
                    // Cotizador Instantáneo
                </p>
                <h2 class="text-4xl md:text-5xl font-extrabold leading-tight text-gray-800 dark:text-white">
                    Calculadora de Lonas
                </h2>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">

                {{-- Formulario de Medidas --}}
                <div
                    class="lg:col-span-7 bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-8 border-l-4 border-amber-400">
                    <h3
                        class="text-xl font-bold text-gray-800 dark:text-white mb-6 border-b border-gray-100 dark:border-gray-700 pb-4">
                        Especificaciones
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Ancho
                                (metros)</label>
                            <div class="relative">
                                <input wire:model.live="width" type="number" step="0.1" min="0.1"
                                    class="w-full pl-4 pr-10 py-3 rounded-lg border border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-amber-400 outline-none transition-colors">
                                <span
                                    class="absolute inset-y-0 right-0 flex items-center pr-4 text-gray-400 font-medium">m</span>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Alto
                                (metros)</label>
                            <div class="relative">
                                <input wire:model.live="height" type="number" step="0.1" min="0.1"
                                    class="w-full pl-4 pr-10 py-3 rounded-lg border border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-amber-400 outline-none transition-colors">
                                <span
                                    class="absolute inset-y-0 right-0 flex items-center pr-4 text-gray-400 font-medium">m</span>
                            </div>
                        </div>
                    </div>

                    <div class="mb-8">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Cantidad de
                            lonas</label>
                        <input wire:model.live="quantity" type="number" min="1"
                            class="w-full md:w-1/2 px-4 py-3 rounded-lg border border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-amber-400 outline-none transition-colors">
                    </div>

                    <h3
                        class="text-xl font-bold text-gray-800 dark:text-white mb-6 border-b border-gray-100 dark:border-gray-700 pb-4">
                        Acabados y Extras
                    </h3>

                    <div class="space-y-4">
                        <label
                            class="flex items-start p-4 border border-red-200 dark:border-red-900/50 bg-red-50/30 dark:bg-red-900/10 rounded-lg cursor-pointer hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors">
                            <div class="flex items-center h-5">
                                <input wire:model.live="isUrgent" type="checkbox"
                                    class="w-5 h-5 text-red-500 bg-white border-gray-300 rounded focus:ring-red-500 cursor-pointer">
                            </div>
                            <div class="ml-3 text-sm">
                                <span
                                    class="font-bold text-red-700 dark:text-red-400 block text-base flex items-center">
                                    <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Servicio Urgente (Prioridad)
                                </span>
                                <span class="text-red-600/80 dark:text-red-400/80 block mt-1">Se agrega un 20% al total
                                    para adelantar tu pedido en la fila de producción.</span>
                            </div>
                        </label>
                    </div>
                </div>

                {{-- Resumen y Total --}}
                <div class="lg:col-span-5">
                    <div
                        class="bg-gray-900 dark:bg-black rounded-2xl shadow-xl p-8 sticky top-8 border-t-4 border-amber-400">
                        <h3 class="text-xl font-bold text-white mb-6 border-b border-gray-700 pb-4">
                            Resumen de Cotización
                        </h3>

                        <div class="space-y-4 mb-8">
                            <div class="flex justify-between text-gray-300">
                                <span>Medidas:</span>
                                <span class="font-medium">{{ number_format((float) $width, 2) }}m x
                                    {{ number_format((float) $height, 2) }}m</span>
                            </div>
                            <div class="flex justify-between text-gray-300">
                                <span>Cantidad:</span>
                                <span class="font-medium">{{ $quantity }} pieza(s)</span>
                            </div>

                            <div
                                class="flex justify-between {{ $this->isMinimumCharge ? 'text-amber-400' : 'text-gray-300' }}">
                                <span>Precio impresión (c/u):</span>
                                <div class="text-right">
                                    @if ($this->isMinimumCharge)
                                        <span class="font-medium">${{ number_format($minPrice, 2) }}</span>
                                        <span class="block text-xs opacity-80">(Cobro mínimo aplicado)</span>
                                    @else
                                        <span
                                            class="font-medium">${{ number_format($this->area * $pricePerSqm, 2) }}</span>
                                    @endif
                                </div>
                            </div>

                            @if ($needsDesign || $needsTubes || $isUrgent)
                                <div class="border-t border-gray-700 pt-4 mt-4 space-y-2">
                                    <p class="text-xs text-gray-500 uppercase tracking-wider font-bold">Cargos
                                        Adicionales</p>
                                    @if ($needsDesign)
                                        <div class="flex justify-between text-gray-300 text-sm">
                                            <span>Diseño</span>
                                            <span>+$150.00</span>
                                        </div>
                                    @endif
                                    @if ($needsTubes)
                                        <div class="flex justify-between text-gray-300 text-sm">
                                            <span>Tubos/Maderas</span>
                                            <span>Incluido en suma</span>
                                        </div>
                                    @endif
                                    @if ($isUrgent)
                                        <div class="flex justify-between text-red-400 font-bold text-sm">
                                            <span>Urgencia (20%)</span>
                                            <span>+${{ number_format($this->urgentFee, 2) }}</span>
                                        </div>
                                    @endif
                                </div>
                            @endif
                        </div>

                        <div class="border-t border-gray-700 pt-6">
                            <p class="text-gray-400 text-sm mb-1">Total Estimado</p>
                            <div class="text-4xl font-extrabold text-amber-400 tracking-tight">
                                ${{ number_format($this->total, 2) }} <span
                                    class="text-lg font-normal text-gray-500">MXN</span>
                            </div>
                        </div>

                        {{-- BOTÓN DE WHATSAPP --}}
                        <a href="{{ $this->whatsappUrl }}" target="_blank"
                            class="w-full mt-8 bg-green-500 hover:bg-green-600 text-white font-bold py-4 px-6 rounded-xl transition-all duration-300 flex items-center justify-center group shadow-lg shadow-green-500/30 hover:-translate-y-1">
                            <svg class="w-6 h-6 mr-2" fill="currentColor" viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893-.001-3.189-1.262-6.187-3.55-8.444" />
                            </svg>
                            Enviar Cotización
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </section>
</div>

<div class="p-2 max-w-[1800px] mx-auto">

    <!-- SECCIÓN DE FILTRO POR RANGO (Emerald Style) -->
    <div class="mb-8 bg-white dark:bg-gray-800 p-6 rounded-xl shadow-md border border-emerald-100 dark:border-gray-700">
        <div class="flex flex-col md:flex-row items-end gap-4">
            <div class="flex-1 w-full">
                <h3 class="text-lg font-bold text-gray-800 dark:text-emerald-400 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                        </path>
                    </svg>
                    Consulta de Ventas por Período
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label
                            class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-1">Desde</label>
                        <input type="date" wire:model.live="fromDate"
                            class="w-full rounded-lg border-gray-200 dark:bg-gray-700 dark:text-white focus:ring-emerald-500 focus:border-emerald-500 transition-all shadow-sm">
                    </div>
                    <div>
                        <label
                            class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-1">Hasta</label>
                        <input type="date" wire:model.live="toDate"
                            class="w-full rounded-lg border-gray-200 dark:bg-gray-700 dark:text-white focus:ring-emerald-500 focus:border-emerald-500 transition-all shadow-sm">
                    </div>
                </div>
            </div>

            <!-- Card de Resultado del Rango -->
            <div
                class="w-full md:w-80 bg-emerald-600 dark:bg-emerald-700 rounded-xl p-4 text-white shadow-lg relative overflow-hidden group">
                <svg class="absolute -right-2 -bottom-2 w-24 h-24 text-emerald-500 opacity-20 group-hover:scale-110 transition-transform"
                    fill="currentColor" viewBox="0 0 20 20">
                    <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z"></path>
                    <path fill-rule="evenodd"
                        d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z"
                        clip-rule="evenodd"></path>
                </svg>
                <div class="relative z-10">
                    <div class="text-[10px] font-black uppercase tracking-widest opacity-80">Total del Período</div>
                    <div class="text-3xl font-black">${{ number_format($rangeEarnings, 2) }}</div>
                    <div class="text-[9px] mt-1 font-bold italic opacity-70">Calculado en tiempo real</div>
                </div>
            </div>
        </div>
    </div>

    <!-- TARJETAS ESTADÍSTICAS ORIGINALES -->
    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4 mb-8">
        <!-- Hoy -->
        <div
            class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-emerald-500 transition-colors duration-200 hover:shadow-md">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-emerald-100 dark:bg-emerald-900/50 text-emerald-600 mr-4">
                    <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <div class="text-xs font-black text-gray-400 uppercase tracking-widest">Total Ventas Hoy</div>
                    <div class="text-2xl font-bold text-gray-800 dark:text-white">
                        ${{ number_format($dailyEarnings, 2) }}</div>
                </div>
            </div>
        </div>

        <!-- Mensual -->
        <div
            class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-blue-500 transition-colors hover:shadow-md">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 dark:bg-blue-900/50 text-blue-600 mr-4">
                    <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
                <div>
                    <div class="text-xs font-black text-gray-400 uppercase tracking-widest">Total Ventas Mes</div>
                    <div class="text-2xl font-bold text-gray-800 dark:text-white">
                        ${{ number_format($monthlyEarnings, 2) }}</div>
                </div>
            </div>
        </div>

        <!-- Stock Bajo -->
        <div
            class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-red-500 transition-colors hover:shadow-md">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-red-100 dark:bg-red-900/50 text-red-600 mr-4">
                    <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <div>
                    <div class="text-xs font-black text-gray-400 uppercase tracking-widest">Stock Bajo</div>
                    <div class="text-2xl font-bold text-gray-800 dark:text-white">{{ $lowStockCount }}</div>
                    <a href="{{ route('admin.reports.low-stock') }}"
                        class="text-[10px] text-red-500 font-bold hover:underline">Ver Alertas</a>
                </div>
            </div>
        </div>

        <!-- Cantidad de Ventas -->
        <div
            class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-purple-500 transition-colors hover:shadow-md">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-purple-100 dark:bg-purple-900/50 text-purple-600 mr-4">
                    <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                </div>
                <div>
                    <div class="text-xs font-black text-gray-400 uppercase tracking-widest">N° Ventas (Mes)</div>
                    <div class="text-2xl font-bold text-gray-800 dark:text-white">{{ $monthlySalesCount }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- SECCIÓN DE ACCESOS RÁPIDOS -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div
            class="lg:col-span-2 bg-emerald-600 dark:bg-emerald-700 rounded-2xl shadow-lg hover:shadow-xl transition-all group overflow-hidden relative">
            <div class="absolute inset-0 bg-black opacity-0 group-hover:opacity-5 transition-opacity"></div>
            <a href="{{ route('admin.sales.create') }}"
                class="flex flex-col items-center justify-center p-12 text-white h-full relative z-10">
                <div class="p-4 bg-emerald-500 rounded-2xl mb-4 group-hover:scale-110 transition-transform shadow-lg">
                    <svg class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                </div>
                <span class="text-3xl font-black tracking-tight">REGISTRAR VENTA</span>
                <span class="text-emerald-100 text-sm mt-2 font-medium opacity-80">Abrir punto de venta (POS)</span>
            </a>
        </div>

        <div class="grid grid-cols-1 gap-4">
            <a href="{{ route('admin.products.create') }}"
                class="bg-white dark:bg-gray-800 p-5 rounded-xl shadow border border-gray-100 dark:border-gray-700 flex items-center justify-between group hover:border-emerald-500 transition-all">
                <div class="flex items-center">
                    <div
                        class="bg-emerald-50 dark:bg-emerald-900/30 p-3 rounded-xl text-emerald-600 mr-4 group-hover:bg-emerald-600 group-hover:text-white transition-all">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                        </svg>
                    </div>
                    <span class="font-black text-gray-700 dark:text-gray-200 text-sm tracking-tight uppercase">Nuevo
                        Producto</span>
                </div>
                <span
                    class="text-emerald-500 opacity-0 group-hover:opacity-100 transition-opacity font-bold text-xl">→</span>
            </a>

            <a href="{{ route('admin.movements.index') }}"
                class="bg-white dark:bg-gray-800 p-5 rounded-xl shadow border border-gray-100 dark:border-gray-700 flex items-center justify-between group hover:border-emerald-500 transition-all">
                <div class="flex items-center">
                    <div
                        class="bg-blue-50 dark:bg-blue-900/30 p-3 rounded-xl text-blue-600 mr-4 group-hover:bg-blue-600 group-hover:text-white transition-all">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                        </svg>
                    </div>
                    <span
                        class="font-black text-gray-700 dark:text-gray-200 text-sm tracking-tight uppercase">Historial
                        Kardex</span>
                </div>
                <span
                    class="text-blue-500 opacity-0 group-hover:opacity-100 transition-opacity font-bold text-xl">→</span>
            </a>
        </div>
    </div>
</div>

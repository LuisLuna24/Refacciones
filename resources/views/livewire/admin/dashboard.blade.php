<div>
    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4 mb-8">
        <div
            class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-green-500 transition-colors duration-200">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 dark:bg-green-900/50 text-green-600 dark:text-green-400 mr-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Ganancias Hoy</div>
                    <div class="text-2xl font-bold text-gray-800 dark:text-white">
                        ${{ number_format($dailyEarnings, 2) }}</div>
                </div>
            </div>
        </div>

        {{-- Tarjeta: Ganancias del Mes --}}
        <div
            class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-blue-500 transition-colors duration-200">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 dark:bg-blue-900/50 text-blue-600 dark:text-blue-400 mr-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
                <div>
                    <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Ganancias Mensuales</div>
                    <div class="text-2xl font-bold text-gray-800 dark:text-white">
                        ${{ number_format($monthlyEarnings, 2) }}</div>
                </div>
            </div>
        </div>

        {{-- Tarjeta: Alerta de Stock Bajo --}}
        <div
            class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-red-500 transition-colors duration-200">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-red-100 dark:bg-red-900/50 text-red-600 dark:text-red-400 mr-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <div>
                    <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Stock Bajo</div>
                    <div class="text-2xl font-bold text-gray-800 dark:text-white">{{ $lowStockCount }}</div>
                    <a href="#" class="text-xs text-red-600 dark:text-red-400 hover:underline">Ver
                        productos</a>
                </div>
            </div>
        </div>

        {{-- Tarjeta: Total Ventas del Mes --}}
        <div
            class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-purple-500 transition-colors duration-200">
            <div class="flex items-center">
                <div
                    class="p-3 rounded-full bg-purple-100 dark:bg-purple-900/50 text-purple-600 dark:text-purple-400 mr-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                </div>
                <div>
                    <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Ventas (Mes)</div>
                    <div class="text-2xl font-bold text-gray-800 dark:text-white">{{ $monthlySalesCount }}</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Sección de Accesos Rápidos --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <div
            class="lg:col-span-2 bg-indigo-600 dark:bg-indigo-700 rounded-lg shadow-lg hover:bg-indigo-700 dark:hover:bg-indigo-600 transition duration-300">
            <a href="{{ route('admin.sales.create') }}"
                class="flex flex-col items-center justify-center p-8 text-white h-full">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mb-2" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                <span class="text-2xl font-bold">Registrar Nueva Venta</span>
                <span class="text-indigo-200 dark:text-indigo-100 text-sm mt-1">Ir al punto de venta (POS)</span>
            </a>
        </div>

        <div class="grid grid-cols-1 gap-4">
            <a href="{{ route('admin.products.create') }}"
                class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow hover:bg-gray-50 dark:hover:bg-gray-700 flex items-center justify-between group transition-colors duration-200">
                <div class="flex items-center">
                    <div
                        class="bg-blue-100 dark:bg-blue-900/50 p-2 rounded-full text-blue-600 dark:text-blue-400 mr-3 group-hover:bg-blue-600 group-hover:text-white transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                        </svg>
                    </div>
                    <span class="font-semibold text-gray-700 dark:text-gray-200">Agregar Producto</span>
                </div>
                <span class="text-gray-400 dark:text-gray-500">→</span>
            </a>

            <a href="{{ route('admin.movements.index') }}"
                class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow hover:bg-gray-50 dark:hover:bg-gray-700 flex items-center justify-between group transition-colors duration-200">
                <div class="flex items-center">
                    <div
                        class="bg-orange-100 dark:bg-orange-900/50 p-2 rounded-full text-orange-600 dark:text-orange-400 mr-3 group-hover:bg-orange-600 group-hover:text-white transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                        </svg>
                    </div>
                    <span class="font-semibold text-gray-700 dark:text-gray-200">Movimientos / Kardex</span>
                </div>
                <span class="text-gray-400 dark:text-gray-500">→</span>
            </a>
        </div>
    </div>

</div>

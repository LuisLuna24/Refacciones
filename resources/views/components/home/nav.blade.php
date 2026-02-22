@php
    $routes = [
        ['name' => 'Inicio', 'route' => 'home', 'routeIs' => 'home'],
        ['name' => 'Nosotros', 'route' => 'nosotros', 'routeIs' => 'nosotros'],
        ['name' => 'Refacciones', 'route' => 'refacciones.index', 'routeIs' => 'refacciones.*'],
        ['name' => 'Contacto', 'route' => 'contacto', 'routeIs' => 'contacto'],
        ['name' => 'Lonas', 'route' => 'lonas', 'routeIs' => 'lonas'],
    ];
@endphp
<nav x-data="{ mobileMenuIsOpen: false }" x-on:click.away="mobileMenuIsOpen = false"
    class="w-full flex items-center justify-between border-b border-neutral-300 px-6 py-4 dark:border-neutral-700 sticky top-0 z-50 bg-neutral-50 dark:bg-neutral-900"
    aria-label="penguin ui menu">
    <!-- Brand Logo -->
    <a href="{{ route('home') }}" class="text-3xl font-extrabold text-neutral-900 dark:text-white">
        <img src="{{ asset('img/logo.webp') }}" alt="logo two brothers" class=" h-12">
    </a>
    <!-- Desktop Menu -->
    <ul class="hidden items-center gap-6 sm:flex">
        @foreach ($routes as $item)
            <li>
                <a href="{{ route($item['route']) }}" @class([
                    // --- Estilos Base (Aplicados a TODOS los enlaces) ---
                    'relative font-semibold transition-all duration-300 ease-in-out group', // El 'group' es clave para el hover
                    'focus:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 focus-visible:ring-orange-500 rounded-sm',

                    // --- Estilos de Indicador (La línea inferior) ---
                    'after:absolute after:bottom-[-8px] after:left-0 after:h-[3px] after:w-full after:rounded-full after:bg-orange-600 after:transition-transform after:duration-300 dark:after:bg-orange-400',

                    // --- ESTADO INACTIVO (Por defecto + Hover) ---
                    'text-neutral-500 hover:text-neutral-900 dark:text-neutral-400 dark:hover:text-white',
                    'after:scale-x-0 group-hover:after:scale-x-75', // La línea se muestra al 75% en hover (inactivo)

                    // --- ESTADO ACTIVO (Si la ruta coincide) ---
                    'text-orange-600 dark:text-orange-400' => request()->routeIs(
                        $item['routeIs']),
                    'after:scale-x-100' => request()->routeIs($item['routeIs']), // La línea se muestra completa (activo)
                ])
                    aria-current="{{ request()->routeIs($item['routeIs']) ? 'page' : 'false' }}">
                    {{ $item['name'] }}
                </a>
            </li>
        @endforeach
        @if (Auth::check() && Auth::user()->type_user_id == 1)
            <li class="group">
                <a href="{{ route('admin.dashboard') }}"
                    class="relative flex items-center space-x-3 px-4 py-3 text-gray-600 dark:text-gray-400 hover:text-amber-600 dark:hover:text-amber-400 transition-colors duration-300 overflow-hidden">
                    <!-- Efecto de fondo hover -->
                    <div
                        class="absolute inset-0 bg-amber-500/0 group-hover:bg-amber-500/5 rounded-xl transition-all duration-500">
                    </div>

                    <!-- Icono -->
                    <div
                        class="relative z-10 p-2 bg-amber-100 dark:bg-amber-900/30 rounded-lg group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-4 h-4 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                        </svg>
                    </div>

                    <!-- Texto -->
                    <span class="relative z-10 font-medium">Panel de Control</span>

                    <!-- Indicador -->
                    <div class="relative z-10 ml-auto w-2 h-2 bg-amber-400 rounded-full group-hover:animate-ping"></div>
                </a>
            </li>
        @endif
    </ul>
    <!-- Mobile Menu Button -->
    <button x-on:click="mobileMenuIsOpen = !mobileMenuIsOpen" x-bind:aria-expanded="mobileMenuIsOpen"
        x-bind:class="mobileMenuIsOpen ? 'fixed top-6 right-6 z-20' : null" type="button"
        class="flex text-neutral-600 dark:text-neutral-300 md:hidden" aria-label="mobile menu"
        aria-controls="mobileMenu">
        <svg x-show="!mobileMenuIsOpen" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
            stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-menu-2">
            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
            <path d="M4 6l16 0" />
            <path d="M4 12l16 0" />
            <path d="M4 18l16 0" />
        </svg>
        <svg x-cloak x-show="mobileMenuIsOpen" xmlns="http://www.w3.org/2000/svg" fill="none" aria-hidden="true"
            viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="size-6">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
        </svg>
    </button>
    <ul x-cloak x-show="mobileMenuIsOpen"
        x-transition:enter="transition motion-reduce:transition-none ease-out duration-300"
        x-transition:enter-start="-translate-y-full" x-transition:enter-end="translate-y-0"
        x-transition:leave="transition motion-reduce:transition-none ease-out duration-300"
        x-transition:leave-start="translate-y-0" x-transition:leave-end="-translate-y-full" id="mobileMenu"
        {{-- CLASES DEL UL CONTENEDOR --}}
        class="fixed max-h-svh overflow-y-auto inset-x-0 top-0 z-10 flex flex-col
           divide-y divide-neutral-200 rounded-b-lg border-b border-neutral-300
           bg-white px-4 pb-6 pt-20 shadow-xl
           dark:divide-neutral-800 dark:border-neutral-800 dark:bg-neutral-900
           sm:hidden">
        {{-- Cambiado md:hidden a sm:hidden para mayor compatibilidad --}}

        @foreach ($routes as $item)
            {{-- EL LI YA NO NECESITA PADDING VERTICAL, se lo damos al enlace --}}
            <li>
                <a href="{{ route($item['route']) }}" @class([
                    // --- Estilos Base ---
                    'block w-full text-left py-3 px-2 text-xl transition-all duration-200 rounded-md', // block, tamaño de fuente (xl) y padding

                    // --- ESTADO INACTIVO (Por defecto + Hover/Focus) ---
                    'font-medium text-neutral-700 hover:bg-neutral-100 hover:text-orange-600',
                    'dark:text-neutral-300 dark:hover:bg-neutral-800 dark:hover:text-orange-400',

                    // --- ESTADO ACTIVO (Si la ruta coincide) ---
                    'font-extrabold text-orange-600 bg-orange-50 dark:text-orange-400 dark:bg-neutral-800' => request()->routeIs(
                        $item['route']),
                ])
                    aria-current="{{ request()->routeIs($item['route']) ? 'page' : 'false' }}">
                    {{ $item['name'] }}
                </a>
            </li>
        @endforeach
    </ul>
</nav>

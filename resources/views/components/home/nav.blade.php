@php
    $routes = [
        ['name' => 'Inicio', 'route' => 'home', 'routeIs' => 'home'],
        ['name' => 'Nosotros', 'route' => 'nosotros', 'routeIs' => 'nosotros'],
        ['name' => 'Refacciones', 'route' => 'refacciones.index', 'routeIs' => 'refacciones.*'],
        ['name' => 'Contacto', 'route' => 'contacto', 'routeIs' => 'contacto'],
        ['name' => 'Lonas', 'route' => 'lonas', 'routeIs' => 'lonas'],
    ];
@endphp

<nav x-data="{ mobileMenuIsOpen: false }" x-on:click.away="mobileMenuIsOpen = false" {{-- Bloquea el scroll cuando el menú está abierto --}}
    x-effect="mobileMenuIsOpen ? document.body.classList.add('overflow-hidden') : document.body.classList.remove('overflow-hidden')"
    class="sticky top-0 z-50 w-full border-b border-neutral-300 bg-white/80 px-6 py-4 backdrop-blur-md dark:border-neutral-700 dark:bg-neutral-900/80"
    aria-label="main navigation">

    <div class="mx-auto flex max-w-7xl items-center justify-between">
        <a href="{{ route('home') }}" class="shrink-0 transition-transform hover:scale-105">
            <img src="{{ asset('img/logo.webp') }}" alt="logo two brothers" class="h-14 w-auto">
        </a>

        <div class="hidden items-center gap-8 sm:flex">
            <ul class="flex items-center gap-6">
                @foreach ($routes as $item)
                    <li>
                        <a href="{{ route($item['route']) }}" @class([
                            'relative py-2 font-semibold transition-all duration-300 ease-in-out group focus:outline-none',
                            'text-neutral-500 hover:text-orange-600 dark:text-neutral-400 dark:hover:text-orange-400',
                            'after:absolute after:bottom-0 after:left-0 after:h-[2px] after:w-full after:origin-right after:scale-x-0 after:bg-orange-600 after:transition-transform after:duration-300 group-hover:after:origin-left group-hover:after:scale-x-100 dark:after:bg-orange-400',
                            'text-orange-600 after:scale-x-100 dark:text-orange-400' => request()->routeIs(
                                $item['routeIs']),
                        ])
                            aria-current="{{ request()->routeIs($item['routeIs']) ? 'page' : 'false' }}">
                            {{ $item['name'] }}
                        </a>
                    </li>
                @endforeach
            </ul>

            {{-- Separador si hay admin --}}
            @if (Auth::check() && Auth::user()->type_user_id == 1)
                <div class="h-6 w-px bg-neutral-300 dark:bg-neutral-700"></div>
                <a href="{{ route('admin.dashboard') }}"
                    class="group flex items-center gap-2 rounded-full bg-amber-100 px-4 py-2 text-sm font-bold text-amber-700 transition-all hover:bg-amber-200 dark:bg-amber-900/40 dark:text-amber-400">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                    <span>Panel</span>
                </a>
            @endif
        </div>

        <button x-on:click="mobileMenuIsOpen = !mobileMenuIsOpen"
            class="relative z-50 flex text-neutral-600 dark:text-neutral-300 sm:hidden"
            :class="mobileMenuIsOpen ? 'fixed right-6' : ''">
            <svg x-show="!mobileMenuIsOpen" class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7" />
            </svg>
            <svg x-cloak x-show="mobileMenuIsOpen" class="h-6 w-6" fill="none" stroke="currentColor"
                viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>

    <template x-teleport="body">
        <div x-show="mobileMenuIsOpen" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-[40] bg-neutral-900/60 backdrop-blur-sm sm:hidden">
        </div>
    </template>

    <ul x-cloak x-show="mobileMenuIsOpen" x-transition:enter="transition cubic-bezier(0.4, 0, 0.2, 1) duration-300"
        x-transition:enter-start="-translate-y-full" x-transition:enter-end="translate-y-0"
        x-transition:leave="transition cubic-bezier(0.4, 0, 0.2, 1) duration-300"
        x-transition:leave-start="translate-y-0" x-transition:leave-end="-translate-y-full"
        class="fixed inset-x-0 top-0 z-[45] flex flex-col gap-2 border-b border-neutral-300 bg-white px-6 pb-8 pt-24 shadow-2xl dark:border-neutral-800 dark:bg-neutral-900 sm:hidden">

        @foreach ($routes as $item)
            <li>
                <a href="{{ route($item['route']) }}" @class([
                    'block w-full rounded-xl px-4 py-4 text-lg font-semibold transition-all',
                    'text-neutral-600 dark:text-neutral-400 active:scale-95',
                    'bg-orange-50 text-orange-600 dark:bg-orange-500/10 dark:text-orange-400' => request()->routeIs(
                        $item['routeIs']),
                ])>
                    {{ $item['name'] }}
                </a>
            </li>
        @endforeach

        @if (Auth::check() && Auth::user()->type_user_id == 1)
            <li class="mt-4 border-t border-neutral-100 pt-4 dark:border-neutral-800">
                <a href="{{ route('admin.dashboard') }}"
                    class="flex items-center justify-between rounded-xl bg-amber-500 p-4 text-white">
                    <span class="font-bold">Panel</span>
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 7l5 5m0 0l-5 5m5-5H6" />
                    </svg>
                </a>
            </li>
        @endif
    </ul>
</nav>

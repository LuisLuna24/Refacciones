<div>
    {{-- Estilos encapsulados --}}
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');

        body {
            font-family: 'Inter', sans-serif;
        }

        /* ¡ELIMINAMOS opacity: 0 DE AQUÍ!
           GSAP se encargará de ocultarlos antes de animarlos.
           Así, si el JS falla, tus productos seguirán viéndose. */

        .pagination-container nav span[aria-current="page"] span {
            background-color: #fbbf24 !important;
            border-color: #fbbf24 !important;
            color: white !important;
        }
    </style>

    <section class="py-16 md:py-24 bg-gray-50 dark:bg-gray-900 min-h-screen">
        {{-- Encabezado --}}
        <div class="text-center max-w-3xl mx-auto mb-12 px-4">
            <p class="text-sm tracking-widest uppercase mb-3 text-amber-400 font-mono fade-in-up">
                // Catálogo
            </p>
            <h2 class="text-4xl md:text-5xl font-extrabold leading-tight text-gray-800 dark:text-white fade-in-up">
                Nuestros Productos
            </h2>
            <p class="text-lg text-gray-600 dark:text-gray-300 mt-6 max-w-2xl mx-auto fade-in-up">
                Calidad y diseño en cada detalle. Encuentra lo que buscas para tu proyecto.
            </p>
        </div>

        {{-- Barra de Herramientas (Buscador y Filtros) --}}
        <div class="max-w-6xl mx-auto px-4 mb-12 fade-in-up">
            <div
                class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6 flex flex-col md:flex-row gap-4 items-center justify-between border-l-4 border-amber-400">
                <div class="relative w-full">
                    <label>Buscar:</label>
                    <x-w-input wire:model.live.debounce.300ms="search" type="text"
                        placeholder="Buscar por nombre o descripción..." />
                </div>
                <div class="flex flex-col gap1">
                    <label>Categoría</label>
                    <select
                        class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                        wire:model.live="category_id">
                        <option value="" disabled>Seleccione una opción.</option>
                        @foreach ($categories as $category)
                            @if ($category->products->count() > 0)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        {{-- Grid de Productos --}}
        <div class="max-w-6xl mx-auto px-4">
            @if ($products->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-12">
                    @foreach ($products as $product)
                        {{--
                            Se agregaron clases de TAILWIND para el Hover:
                            hover:-translate-y-2 hover:shadow-[0_20px_35px_-8px_rgba(0,0,0,0.18)] duration-300
                        --}}
                        <div wire:key="product-{{ $product->id }}"
                            class="product-card bg-white dark:bg-gray-800 rounded-2xl shadow-lg transition-all duration-300 hover:-translate-y-2 hover:shadow-[0_20px_35px_-8px_rgba(0,0,0,0.18)] overflow-hidden border-l-4 border-amber-400 hover:border-amber-400 flex flex-col fade-in-up h-full group">

                            <div class="relative h-56 bg-gray-100 dark:bg-gray-700 overflow-hidden">
                                <img src="{{ $product->image }}" alt="{{ $product->name }}"
                                    class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">

                                @if ($product->category)
                                    <div
                                        class="absolute top-4 right-4 bg-gray-900/80 backdrop-blur-sm text-amber-400 text-xs font-bold px-3 py-1.5 rounded-full shadow-sm border border-amber-400/30">
                                        {{ $product->category->name }}
                                    </div>
                                @endif
                                @if ($product->stock <= 0)
                                    <div
                                        class="absolute top-4 left-4 bg-red-500 text-white text-xs font-bold px-3 py-1 rounded-full shadow-sm">
                                        Agotado
                                    </div>
                                @endif
                            </div>

                            <div class="p-8 flex flex-col flex-grow relative">
                                <div class="flex justify-between items-start mb-3 gap-2">
                                    <h3 class="text-xl font-bold text-gray-800 dark:text-white leading-tight line-clamp-2"
                                        title="{{ $product->name }}">
                                        {{ $product->name }}
                                    </h3>
                                </div>
                                <div class="mb-4">
                                    <span class="text-2xl font-extrabold text-amber-500 block">
                                        ${{ number_format($product->price, 2) }}
                                    </span>
                                    @if ($product->sku)
                                        <span class="text-xs text-gray-400 font-mono">SKU: {{ $product->sku }}</span>
                                    @endif
                                </div>
                                <p
                                    class="text-gray-600 dark:text-gray-300 leading-relaxed mb-6 flex-grow text-sm line-clamp-3">
                                    {{ $product->description }}
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="pagination-container fade-in-up">
                    {{ $products->links() }}
                </div>
            @else
                <div class="text-center py-16 fade-in-up">
                    <div
                        class="bg-white dark:bg-gray-800 rounded-2xl p-8 inline-block shadow-lg border-l-4 border-amber-400">
                        <svg class="w-16 h-16 text-amber-400 mx-auto mb-4" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                            </path>
                        </svg>
                        <h3 class="text-xl font-bold text-gray-800 dark:text-white">No se encontraron productos</h3>
                        <p class="text-gray-600 dark:text-gray-300 mt-2">Intenta ajustar los filtros de búsqueda.</p>
                        <button wire:click="$set('search', '')"
                            class="mt-4 text-amber-500 hover:text-amber-600 font-medium underline">
                            Limpiar búsqueda
                        </button>
                    </div>
                </div>
            @endif
        </div>
    </section>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>

    @script
        <script>
            const runEntranceAnimations = () => {
                // Verificamos que GSAP esté definido antes de intentar usarlo
                if (typeof gsap === 'undefined') return;

                // Usamos gsap.from() en lugar de fromTo() porque ya quitamos el opacity:0 de CSS
                gsap.from('.fade-in-up, .product-card', {
                    opacity: 0,
                    y: 20,
                    duration: 0.5,
                    stagger: 0.08,
                    ease: "power2.out",
                    clearProps: "all" // Limpia el estilo in-line al terminar para evitar bugs con Livewire
                });
            };

            // 1. Un pequeño intervalo de seguridad para esperar a que el CDN de GSAP cargue
            const waitForGsap = setInterval(() => {
                if (window.gsap) {
                    clearInterval(waitForGsap);
                    runEntranceAnimations();
                }
            }, 50);

            // 2. Escuchamos específicamente la actualización del DOM de Livewire v3
            Livewire.hook('morph.updated', ({
                component,
                el
            }) => {
                runEntranceAnimations();
            });
        </script>
    @endscript
</div>

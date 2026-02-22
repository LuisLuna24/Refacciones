@extends('layouts.guest')
@section('content')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');

        body {
            font-family: 'Inter', sans-serif;
        }

        /* 🎨 Clases base optimizadas para la animación inicial */
        /* Reducimos el desplazamiento inicial (de 30px a 15px/20px) para un efecto más sutil */
        .fade-in-up {
            opacity: 0;
            transform: translateY(20px);
            /* 20px para los títulos */
        }

        .fade-in-left {
            opacity: 0;
            transform: translateX(-15px);
            /* 15px para las tarjetas laterales */
        }

        .fade-in-right {
            opacity: 0;
            transform: translateX(15px);
            /* 15px para las tarjetas laterales */
        }

        .scale-in {
            opacity: 0;
            transform: scale(0.95);
            /* Menos reducción de escala (de 0.9 a 0.95) */
        }
    </style>
    <section class="py-16 md:py-24">
        <div class="text-center max-w-3xl mx-auto mb-12 px-4">
            <p class="text-sm tracking-widest uppercase mb-3 text-amber-400 font-mono fade-in-up">
                // Nuestra Promesa
            </p>
            <h2 id="promise-title"
                class="text-4xl md:text-5xl font-extrabold leading-tight text-gray-800 dark:text-white fade-in-up">
                Quienes Somos?
            </h2>
        </div>

        <div class="max-w-6xl mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-16">
                <div
                    class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 p-8 border-l-4 border-amber-400 fade-in-left">
                    <div class="flex items-center mb-6">
                        <div
                            class="w-12 h-12 rounded-full bg-amber-100 dark:bg-amber-900 flex items-center justify-center mr-4">
                            <svg class="w-6 h-6 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                                </path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800 dark:text-white">Sobre nosotros</h3>
                    </div>
                    <p class="text-gray-600 dark:text-gray-300 leading-relaxed">
                        "Transformar las ideas de nuestros clientes en productos personalizados y soluciones publicitarias
                        de alto impacto. Nos dedicamos a ofrecer una calidad excepcional en materiales y diseño, desde
                        serigrafía hasta wrapping vehicular, garantizando precios accesibles y un servicio al cliente
                        cercano y confiable."
                    </p>
                </div>

                <div
                    class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 p-8 border-l-4 border-amber-400 fade-in-up">
                    <div class="flex items-center mb-6">
                        <div
                            class="w-12 h-12 rounded-full bg-amber-100 dark:bg-amber-900 flex items-center justify-center mr-4">
                            <svg class="w-6 h-6 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800 dark:text-white">Misión</h3>
                    </div>
                    <p class="text-gray-600 dark:text-gray-300 leading-relaxed">
                        "Transformar las ideas de nuestros clientes en productos personalizados y soluciones publicitarias
                        de alto impacto. Nos dedicamos a ofrecer una calidad excepcional en materiales y diseño, desde
                        serigrafía hasta wrapping vehicular, garantizando precios accesibles y un servicio al cliente
                        cercano y confiable."
                    </p>
                </div>

                <div
                    class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 p-8 border-l-4 border-amber-400 fade-in-right">
                    <div class="flex items-center mb-6">
                        <div
                            class="w-12 h-12 rounded-full bg-amber-100 dark:bg-amber-900 flex items-center justify-center mr-4">
                            <svg class="w-6 h-6 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                </path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800 dark:text-white">Visión</h3>
                    </div>
                    <p class="text-gray-600 dark:text-gray-300 leading-relaxed">
                        "Ser la marca de personalización más reconocida y recomendada de la región. Visualizamos 'Two
                        Brothers' expandiendo sus capacidades técnicas, atendiendo a una cartera de clientes diversa y leal
                        que nos elige por nuestra calidad de diseño y nuestro compromiso con su satisfacción."
                    </p>
                </div>
            </div>

            <div
                class="bg-gradient-to-r from-amber-50 to-amber-100 dark:from-amber-900/20 dark:to-amber-800/20 rounded-2xl p-8 md:p-12 scale-in">
                <h3 class="text-2xl font-bold text-center mb-8 text-gray-800 dark:text-white fade-in-up">Nuestros Valores
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                    <div class="text-center value-item">
                        <div
                            class="w-16 h-16 mx-auto rounded-full bg-amber-400 dark:bg-amber-600 flex items-center justify-center mb-4">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z">
                                </path>
                            </svg>
                        </div>
                        <h4 class="font-semibold text-gray-800 dark:text-white mb-2">Calidad</h4>
                        <p class="text-sm text-gray-600 dark:text-gray-300">Materiales y acabados de primera</p>
                    </div>
                    <div class="text-center value-item">
                        <div
                            class="w-16 h-16 mx-auto rounded-full bg-amber-400 dark:bg-amber-600 flex items-center justify-center mb-4">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                                </path>
                            </svg>
                        </div>
                        <h4 class="font-semibold text-gray-800 dark:text-white mb-2">Servicio</h4>
                        <p class="text-sm text-gray-600 dark:text-gray-300">Atención personalizada y cercana</p>
                    </div>
                    <div class="text-center value-item">
                        <div
                            class="w-16 h-16 mx-auto rounded-full bg-amber-400 dark:bg-amber-600 flex items-center justify-center mb-4">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1">
                                </path>
                            </svg>
                        </div>
                        <h4 class="font-semibold text-gray-800 dark:text-white mb-2">Precios</h4>
                        <p class="text-sm text-gray-600 dark:text-gray-300">Accesibles y competitivos</p>
                    </div>
                    <div class="text-center value-item">
                        <div
                            class="w-16 h-16 mx-auto rounded-full bg-amber-400 dark:bg-amber-600 flex items-center justify-center mb-4">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                        </div>
                        <h4 class="font-semibold text-gray-800 dark:text-white mb-2">Innovación</h4>
                        <p class="text-sm text-gray-600 dark:text-gray-300">Técnicas y materiales vanguardistas</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
    <script>
        // 🚀 Script GSAP optimizado para una animación secuencial y suave

        document.addEventListener('DOMContentLoaded', function() {
            const tl = gsap.timeline({
                defaults: {
                    ease: "power2.out"
                }
            });

            // 1. Animación del Encabezado (Subtítulo y Título)
            // Se utiliza .fade-in-up en ambos, pero con un y más pequeño (20px) para sutileza.
            tl.fromTo('.text-center .fade-in-up', {
                opacity: 0,
                y: 20
            }, {
                opacity: 1,
                y: 0,
                duration: 0.5, // Duración suave
                stagger: 0.1, // Pequeño retraso entre el subtítulo y el título
            });

            // 2. Animación de las Tres Tarjetas Principales
            // Aparecen las 3 tarjetas casi al mismo tiempo, con movimientos sutiles.
            tl.fromTo('.fade-in-left, .fade-in-up, .fade-in-right', {
                opacity: 0,
                x: (i, target) => {
                    if (target.classList.contains('fade-in-left')) return -15; // Desde -15px
                    if (target.classList.contains('fade-in-right')) return 15; // Desde 15px
                    return 0;
                },
                y: (i, target) => {
                    // Solo la tarjeta central tiene un pequeño movimiento vertical (15px)
                    return target.classList.contains('fade-in-up') ? 15 : 0;
                }
            }, {
                opacity: 1,
                x: 0,
                y: 0,
                duration: 0.5,
                stagger: 0.15, // Pequeño escalonamiento entre las 3 tarjetas
                // Sobrelapamiento con la animación anterior para un flujo continuo
            }, "<0.2"); // Inicia 0.2 segundos después de que el título termine.

            // 3. Animación del Bloque de Valores (scale-in)
            // El contenedor entero aparece con un pequeño efecto de 'pop' con back.out.
            tl.fromTo('.scale-in', {
                opacity: 0,
                scale: 0.95
            }, {
                opacity: 1,
                scale: 1,
                duration: 0.6,
                ease: "back.out(1.5)" // Rebote suave
            }, "<0.3"); // Inicia 0.3 segundos después de que las tarjetas terminen

            // 4. Animación de los 4 Iconos de Valor
            // Aparecen escalonadamente desde abajo dentro del contenedor de Valores.
            tl.fromTo('.value-item', {
                opacity: 0,
                y: 10
            }, {
                opacity: 1,
                y: 0,
                duration: 0.3,
                stagger: 0.08, // Rápido escalonamiento de los 4 valores
            }, "<0.1"); // Inicia 0.1 segundos después de que el contenedor de valores aparezca

            // 🎁 Hover de las tarjetas principales (se mantiene suave)
            document.querySelectorAll('.grid-cols-1 > div, .md\\:grid-cols-3 > div').forEach(card => {
                // Aseguramos que solo las tarjetas principales tengan el efecto
                if (card.classList.contains('bg-white') || card.classList.contains('dark:bg-gray-800')) {
                    card.addEventListener('mouseenter', () => {
                        gsap.to(card, {
                            y: -4, // Movimiento sutil (-4px)
                            boxShadow: "0 18px 30px -5px rgba(0, 0, 0, 0.15)", // Sombra más prominente
                            duration: 0.2,
                            ease: "power2.out"
                        });
                    });

                    card.addEventListener('mouseleave', () => {
                        gsap.to(card, {
                            y: 0,
                            boxShadow: "0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05)",
                            duration: 0.2,
                            ease: "power2.out"
                        });
                    });
                }
            });
        });
    </script>
@endsection

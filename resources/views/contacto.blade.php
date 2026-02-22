@extends('layouts.guest')
@section('content')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');

        body {
            font-family: 'Inter', sans-serif;
        }

        /* Clases para las animaciones iniciales (desplazamientos aún más sutiles) */
        .fade-in-up {
            opacity: 0;
            transform: translateY(15px);
            /* Reducido de 20px a 15px */
        }

        .fade-in-left {
            opacity: 0;
            transform: translateX(-15px);
            /* Reducido de 20px a 15px */
        }

        .fade-in-right {
            opacity: 0;
            transform: translateX(15px);
            /* Reducido de 20px a 15px */
        }

        .scale-in {
            opacity: 0;
            transform: scale(0.97);
            /* Incrementado de 0.95 a 0.97 para menos reducción inicial */
        }

        /* Animación de pulso más suave para WhatsApp */
        @keyframes pulse-whatsapp {

            0%,
            100% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.02);
                /* Reducido de 1.03 a 1.02 para más sutileza */
            }
        }

        .pulse-whatsapp {
            animation: pulse-whatsapp 1.8s infinite;
            /* Aumentado a 1.8s para un pulso más lento y suave */
        }
    </style>
    <section class="py-16 md:py-24">
        <div class="text-center max-w-3xl mx-auto mb-12 px-4">
            <p class="text-sm tracking-widest uppercase mb-3 text-amber-400 font-mono fade-in-up">
                // Contáctanos
            </p>
            <h2 id="contact-title"
                class="text-4xl md:text-5xl font-extrabold leading-tight text-gray-800 dark:text-white fade-in-up">
                Estamos Aquí Para Ayudarte
            </h2>
            <p class="text-lg text-gray-600 dark:text-gray-300 mt-6 max-w-2xl mx-auto fade-in-up">
                ¿Tienes un proyecto en mente? Nos encanta escuchar nuevas ideas y convertir visiones en realidad.
            </p>
        </div>

        <div class="max-w-6xl mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-16">
                <div
                    class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg hover:shadow-xl transition-all duration-200 p-8 border-l-4 border-green-500 fade-in-left pulse-whatsapp">
                    <div class="flex items-center mb-6">
                        <div
                            class="w-12 h-12 rounded-full bg-green-100 dark:bg-green-900 flex items-center justify-center mr-4">
                            <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="currentColor" viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893-.001-3.189-1.262-6.187-3.55-8.444" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800 dark:text-white">WhatsApp</h3>
                    </div>
                    <p class="text-gray-600 dark:text-gray-300 leading-relaxed mb-4">
                        Chatea con nosotros directamente para respuestas rápidas y atención personalizada.
                    </p>
                    <div class="mt-6">
                        <a href="https://wa.me/5632220120" target="_blank"
                            class="inline-flex items-center justify-center w-full bg-green-500 hover:bg-green-600 text-white font-medium py-3 px-6 rounded-lg transition-colors duration-200">
                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893-.001-3.189-1.262-6.187-3.55-8.444" />
                            </svg>
                            Enviar Mensaje
                        </a>
                    </div>
                </div>

                <div
                    class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg hover:shadow-xl transition-all duration-200 p-8 border-l-4 border-amber-400 fade-in-up">
                    <div class="flex items-center mb-6">
                        <div
                            class="w-12 h-12 rounded-full bg-amber-100 dark:bg-amber-900 flex items-center justify-center mr-4">
                            <svg class="w-6 h-6 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                                </path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800 dark:text-white">Correo Electrónico</h3>
                    </div>
                    <p class="text-gray-600 dark:text-gray-300 leading-relaxed mb-4">
                        Envíanos un correo para consultas detalladas, cotizaciones o propuestas de colaboración.
                    </p>
                    <div class="mt-6">
                        <a href="mailto:twobrothers37@gmail.com"
                            class="inline-flex items-center justify-center w-full bg-amber-500 hover:bg-amber-600 text-white font-medium py-3 px-6 rounded-lg transition-colors duration-200">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                                </path>
                            </svg>
                            Enviar Correo
                        </a>
                    </div>
                </div>

                <div
                    class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg hover:shadow-xl transition-all duration-200 p-8 border-l-4 border-blue-500 fade-in-right">
                    <div class="flex items-center mb-6">
                        <div
                            class="w-12 h-12 rounded-full bg-blue-100 dark:bg-blue-900 flex items-center justify-center mr-4">
                            <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z">
                                </path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800 dark:text-white">Teléfono</h3>
                    </div>
                    <p class="text-gray-600 dark:text-gray-300 leading-relaxed mb-4">
                        Llámanos para conversar directamente sobre tu proyecto y recibir asesoramiento personalizado.
                    </p>
                    <div class="mt-6">
                        <a href="tel:5632220120"
                            class="inline-flex items-center justify-center w-full bg-blue-500 hover:bg-blue-600 text-white font-medium py-3 px-6 rounded-lg transition-colors duration-200">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z">
                                </path>
                            </svg>
                            Llamar Ahora
                        </a>
                    </div>
                </div>
            </div>

            <div
                class="bg-gradient-to-r from-amber-50 to-amber-100 dark:from-amber-900/20 dark:to-amber-800/20 rounded-2xl p-8 md:p-12 scale-in">
                <h3 class="text-2xl font-bold text-center mb-8 text-gray-800 dark:text-white fade-in-up">Síguenos en Redes
                    Sociales</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="text-center social-item">
                        <a href="https://www.facebook.com/profile.php?id=61581596961267" target="_blank"
                            class="block bg-white dark:bg-gray-800 rounded-xl p-6 shadow-md hover:shadow-lg transition-all duration-200 hover:-translate-y-1">
                            <div
                                class="w-16 h-16 mx-auto rounded-full bg-blue-100 dark:bg-blue-900 flex items-center justify-center mb-4">
                                <svg class="w-8 h-8 text-blue-600 dark:text-blue-400" fill="currentColor"
                                    viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" />
                                </svg>
                            </div>
                            <h4 class="font-semibold text-gray-800 dark:text-white mb-2">Facebook</h4>
                            <p class="text-sm text-gray-600 dark:text-gray-300">@TwoBrothers</p>
                        </a>
                    </div>

                    <div class="text-center social-item">
                        <a href="https://www.instagram.com/twobroters1440?igsh=MWpmbmlxemtkdXN6Mg==" target="_blank"
                            class="block bg-white dark:bg-gray-800 rounded-xl p-6 shadow-md hover:shadow-lg transition-all duration-200 hover:-translate-y-1">
                            <div
                                class="w-16 h-16 mx-auto rounded-full bg-pink-100 dark:bg-pink-900 flex items-center justify-center mb-4">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="currentColor"
                                    class="icon icon-tabler icons-tabler-filled icon-tabler-brand-instagram w-8 h-8 text-pink-600 dark:text-pink-400">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path
                                        d="M16 3a5 5 0 0 1 5 5v8a5 5 0 0 1 -5 5h-8a5 5 0 0 1 -5 -5v-8a5 5 0 0 1 5 -5zm-4 5a4 4 0 0 0 -3.995 3.8l-.005 .2a4 4 0 1 0 4 -4m4.5 -1.5a1 1 0 0 0 -.993 .883l-.007 .127a1 1 0 0 0 1.993 .117l.007 -.127a1 1 0 0 0 -1 -1" />
                                </svg>
                            </div>
                            <h4 class="font-semibold text-gray-800 dark:text-white mb-2">Instagram</h4>
                            <p class="text-sm text-gray-600 dark:text-gray-300">@twobroters1440s</p>
                        </a>
                    </div>

                    <div class="text-center social-item">
                        <a href="https://www.tiktok.com/@two.brothers1440?_t=ZS-90xV7yezbD2&_r=1" target="_blank"
                            class="block bg-white dark:bg-gray-800 rounded-xl p-6 shadow-md hover:shadow-lg transition-all duration-200 hover:-translate-y-1">
                            <div
                                class="w-16 h-16 mx-auto rounded-full bg-gray-800 dark:bg-gray-700 flex items-center justify-center mb-4">
                                <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 24 24"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.05-2.89-.35-4.2-.97-.57-.26-1.1-.59-1.62-.93-.01 2.92.01 5.84-.02 8.75-.08 1.4-.54 2.79-1.35 3.94-1.31 1.92-3.58 3.17-5.91 3.21-1.43.08-2.86-.31-4.08-1.03-2.02-1.19-3.44-3.37-3.65-5.71-.02-.5-.03-1-.01-1.49.18-1.9 1.12-3.72 2.58-4.96 1.66-1.44 3.98-2.13 6.15-1.72.02 1.48-.04 2.96-.04 4.44-.99-.32-2.15-.23-3.02.37-.63.41-1.11 1.04-1.36 1.75-.21.51-.15 1.07-.14 1.61.24 1.64 1.82 3.02 3.5 2.87 1.12-.01 2.19-.66 2.77-1.61.19-.33.4-.67.41-1.06.1-1.79.06-3.57.07-5.36.01-4.03-.01-8.05.02-12.07z" />
                                </svg>
                            </div>
                            <h4 class="font-semibold text-gray-800 dark:text-white mb-2">TikTok</h4>
                            <p class="text-sm text-gray-600 dark:text-gray-300">@two.brothers1440</p>
                        </a>
                    </div>
                </div>
            </div>

            <div class="mt-16 text-center fade-in-up">
                <div class="bg-white dark:bg-gray-800 rounded-2xl p-8 shadow-lg">
                    <h3 class="text-2xl font-bold text-gray-800 dark:text-white mb-6">Información Adicional</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 text-left">
                        <div>
                            <h4 class="font-semibold text-amber-500 mb-3">Horario de Atención</h4>
                            <p class="text-gray-600 dark:text-gray-300">Lunes, Miercoles, Jueves y Viernes: 10:00 AM - 5:00
                                PM</p>
                            <p class="text-gray-600 dark:text-gray-300">Martes: 10:00 AM - 7:00 PM</p>
                            <p class="text-gray-600 dark:text-gray-300">Sábados: 9:00 AM - 2:00 PM</p>
                        </div>
                        <div>
                            <h4 class="font-semibold text-amber-500 mb-3">Ubicación</h4>
                            <p class="text-gray-600 dark:text-gray-300">Álvaro Obregón, Cuatro Vientos, 56589</p>
                            <p class="text-gray-600 dark:text-gray-300"> San Jerónimo Cuatro Vientos, Méx.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const tl = gsap.timeline({
                defaults: {
                    ease: "power2.out"
                }
            }); // Establecer un ease por defecto

            // Animación del título y subtítulo
            tl.fromTo('.text-center .fade-in-up', {
                opacity: 0,
                y: 15 // Movimiento aún más sutil
            }, {
                opacity: 1,
                y: 0,
                duration: 0.6, // Ligeramente más largo
                stagger: 0.12, // Ligeramente más espaciado
                ease: "power3.out" // Un ease más suave
            });

            // Animación de las tarjetas principales (WhatsApp, Correo, Teléfono)
            tl.fromTo('.fade-in-left, .fade-in-right, .fade-in-up', {
                opacity: 0,
                x: (i, target) => {
                    if (target.classList.contains('fade-in-left')) return -15; // Movimiento más sutil
                    if (target.classList.contains('fade-in-right')) return 15; // Movimiento más sutil
                    return 0;
                },
                y: (i, target) => {
                    return target.classList.contains('fade-in-up') ? 15 : 0; // Movimiento más sutil
                }
            }, {
                opacity: 1,
                x: 0,
                y: 0,
                duration: 0.7, // Ligeramente más largo
                stagger: 0.15, // Más espaciado
                ease: "power3.out" // Un ease más suave
            }, "<0.2"); // Overlap ligeramente mayor para un flujo continuo

            // Animación de la sección de redes sociales (el contenedor completo)
            tl.fromTo('.scale-in', {
                opacity: 0,
                scale: 0.97 // Menos reducción inicial
            }, {
                opacity: 1,
                scale: 1,
                duration: 0.6, // Ligeramente más largo
                ease: "back.out(1.2)" // Rebote más sutil
            }, "<0.3"); // Overlap ligeramente mayor

            // Animación de los elementos de redes sociales individuales
            // Usamos una nueva clase 'social-item' para ser más específicos y evitar conflictos
            tl.fromTo('.social-item', {
                opacity: 0,
                y: 10 // Movimiento más sutil
            }, {
                opacity: 1,
                y: 0,
                duration: 0.5, // Ligeramente más largo
                stagger: 0.1, // Más espaciado
                ease: "power2.out" // Un ease suave
            }, "<0.2"); // Overlap ligeramente mayor

            // Animación del bloque de información adicional (Horario/Ubicación)
            // Usamos la misma clase fade-in-up que ya estaba, pero como último paso para la secuencia.
            tl.fromTo('.mt-16.fade-in-up', {
                opacity: 0,
                y: 15 // Movimiento más sutil
            }, {
                opacity: 1,
                y: 0,
                duration: 0.6, // Ligeramente más largo
                ease: "power3.out" // Un ease más suave
            }, "<0.3"); // Overlap con la animación anterior

            // Efectos de hover mejorados con GSAP (más suaves)
            document.querySelectorAll('.grid-cols-1 > div, .md\\:grid-cols-3 > div').forEach(card => {
                // Aseguramos que solo las tarjetas principales y de redes sociales tengan el efecto
                if (card.classList.contains('bg-white') || card.classList.contains('dark:bg-gray-800')) {
                    card.addEventListener('mouseenter', () => {
                        // Evitamos que el pulso y el hover interfieran
                        if (!card.classList.contains('pulse-whatsapp')) {
                            gsap.to(card, {
                                y: -6, // Un poco más de elevación para notarse
                                boxShadow: "0 20px 35px -8px rgba(0, 0, 0, 0.18)", // Sombra un poco más prominente
                                duration: 0.3, // Ligeramente más largo para suavidad
                                ease: "power2.out"
                            });
                        }
                    });

                    card.addEventListener('mouseleave', () => {
                        if (!card.classList.contains('pulse-whatsapp')) {
                            gsap.to(card, {
                                y: 0,
                                boxShadow: "0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05)",
                                duration: 0.3, // Ligeramente más largo para suavidad
                                ease: "power2.out"
                            });
                        }
                    });
                }
            });
        });
    </script>
@endsection

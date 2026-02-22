<footer class="" aria-labelledby="footer-heading">
    <h2 id="footer-heading" class="sr-only">Footer</h2>
    <div class="mx-auto max-w-7xl px-6 pb-8 pt-16 sm:pt-24 lg:px-8 lg:pt-32">

        <div class="xl:grid xl:grid-cols-3 xl:gap-8">
            <div class="space-y-4">
                <h1 class="text-3xl font-black leading-tight font-marker text-gray-900 dark:text-white">
                    <span class="">TWO</span> <span class="text-amber-600 dark:text-amber-400">BROTHERS</span>
                </h1>
                <p class="text-sm leading-6 text-gray-500 dark:text-gray-400 max-w-xs">
                    Diseñamos el arte que te hace único. Stickers y diseños de alto impacto para tu máquina.
                </p>
            </div>
            <div class="mt-16 grid grid-cols-2 gap-8 xl:col-span-2 xl:mt-0">
                <div class="md:grid md:grid-cols-2 md:gap-8">
                    <div>
                        <h3 class="text-sm font-semibold leading-6 text-gray-900 dark:text-white">Navegación</h3>
                        <ul role="list" class="mt-6 space-y-4">
                            <li>
                                <a href="{{ route('home') }}"
                                    class="text-sm leading-6 text-gray-600 hover:text-amber-600 dark:text-gray-400 dark:hover:text-amber-400 transition-colors">Inicio</a>
                            </li>
                            <li><a href="{{ route('refacciones.index') }}"
                                    class="text-sm leading-6 text-gray-600 hover:text-amber-600 dark:text-gray-400 dark:hover:text-amber-400 transition-colors">Productos</a>
                            </li>
                            <li><a href="{{ route('nosotros') }}"
                                    class="text-sm leading-6 text-gray-600 hover:text-amber-600 dark:text-gray-400 dark:hover:text-amber-400 transition-colors">Nosotros</a>
                            </li>
                            <li><a href="{{ route('contacto') }}"
                                    class="text-sm leading-6 text-gray-600 hover:text-amber-600 dark:text-gray-400 dark:hover:text-amber-400 transition-colors">Contacto</a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="md:grid md:grid-cols-2 md:gap-8">
                    <div>
                        <h3 class="text-sm font-semibold leading-6 text-gray-900 dark:text-white">Síguenos</h3>
                        <div class="flex items-center space-x-6 mt-6">
                            <a href="https://www.instagram.com/twobroters1440"
                                class="text-gray-500 hover:text-amber-600 dark:text-gray-400 dark:hover:text-amber-400 transition-colors"><span
                                    class="sr-only">Instagram</span><svg xmlns="http://www.w3.org/2000/svg"
                                    width="24" height="24" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round"
                                    class="icon icon-tabler icons-tabler-outline icon-tabler-brand-instagram w-6 h-6">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path
                                        d="M4 8a4 4 0 0 1 4 -4h8a4 4 0 0 1 4 4v8a4 4 0 0 1 -4 4h-8a4 4 0 0 1 -4 -4z" />
                                    <path d="M9 12a3 3 0 1 0 6 0a3 3 0 0 0 -6 0" />
                                    <path d="M16.5 7.5v.01" />
                                </svg></a>
                            <a href="https://www.facebook.com/profile.php?id=61581596961267"
                                class="text-gray-500 hover:text-amber-600 dark:text-gray-400 dark:hover:text-amber-400 transition-colors"><span
                                    class="sr-only">Facebook</span><svg xmlns="http://www.w3.org/2000/svg"
                                    width="24" height="24" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round"
                                    class="icon icon-tabler icons-tabler-outline icon-tabler-brand-facebook w-6 h-6">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path
                                        d="M7 10v4h3v7h4v-7h3l1 -4h-4v-2a1 1 0 0 1 1 -1h3v-4h-3a5 5 0 0 0 -5 5v2h-3" />
                                </svg></a>
                            <a href="https://www.tiktok.com/@two.brothers1440?_t=ZS-903lKdlJKJp&_r=1"
                                class="text-gray-500 hover:text-amber-600 dark:text-gray-400 dark:hover:text-amber-400 transition-colors"><span
                                    class="sr-only">TikTok</span><svg xmlns="http://www.w3.org/2000/svg" width="24s"
                                    height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                    class="icon icon-tabler icons-tabler-outline icon-tabler-brand-tiktok w-6 h-6">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path
                                        d="M21 7.917v4.034a9.948 9.948 0 0 1 -5 -1.951v4.5a6.5 6.5 0 1 1 -8 -6.326v4.326a2.5 2.5 0 1 0 4 2v-11.5h4.083a6.005 6.005 0 0 0 4.917 4.917z" />
                                </svg></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div
            class="mt-16 border-t border-gray-200 dark:border-white/10 pt-8 sm:mt-20 lg:mt-24 flex flex-col items-center gap-4 sm:flex-row sm:justify-between">

            <p class="text-xs leading-5 text-gray-500 dark:text-gray-400">&copy; {{ date('Y') }} Two Brothers. Todos los derechos
                reservados.</p>

            <p class="text-xs leading-5 text-gray-500 dark:text-gray-400">
                Desarrollado por
                <a href="https://lunaweb.com.mx" target="_blank" rel="noopener noreferrer"
                    class="font-semibold text-amber-600 dark:text-amber-400 hover:text-amber-700 dark:hover:text-amber-300 transition-colors">Luna Web</a>.
            </p>

        </div>
    </div>
</footer>

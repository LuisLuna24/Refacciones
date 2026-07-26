<div x-data="priceManager({ initialPrices: @js($prices) })"
    class="w-full mx-auto space-y-8 bg-white dark:bg-gray-900 p-6 md:p-8 rounded-2xl shadow-lg shadow-gray-200/50 dark:shadow-none border border-gray-100 dark:border-gray-800">

    <section>
        <div class="flex items-center gap-2 border-b border-gray-200 dark:border-gray-800 pb-3 mb-6">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor" class="w-5 h-5 text-blue-600 dark:text-blue-400">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M9.568 3H5.25A2.25 2.25 0 0 0 3 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 0 0 5.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 0 0 9.568 3Z" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6Z" />
            </svg>
            <h2 class="text-lg font-bold text-gray-800 dark:text-gray-100 tracking-tight">
                Detalles del Material
            </h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <x-w-input wire:model.defer="name" label="Nombre del vinil" placeholder="Ejemplo: Vinil Textil Básico" />
            <x-w-input wire:model.defer="pressure" type="number" min="0" step="0.01"
                label="Presión de corte (g)" placeholder="Ejemplo: 120" />
            <x-w-input wire:model.defer="unity" label="Unidad de medida"
                placeholder="Ejemplo: metro, pieza, paquete, resma, kg, etc" />
            <x-w-input wire:model.defer="price_default" type="number" min="0" step="0.01"
                label="Precio por defecto" placeholder="Ejemplo: 120" />
        </div>
    </section>

    <section>
        <div class="flex items-center gap-2 border-b border-gray-200 dark:border-gray-800 pb-3 mb-6">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor" class="w-5 h-5 text-emerald-600 dark:text-emerald-400">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M12 6v12m-3-2.818.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
            </svg>
            <h2 class="text-lg font-bold text-gray-800 dark:text-gray-100 tracking-tight">
                Estructura de Precios
            </h2>
        </div>

        <div
            class="grid grid-cols-1 md:grid-cols-12 gap-5 items-end bg-slate-50 dark:bg-gray-800/40 p-5 rounded-xl border border-slate-200 dark:border-gray-700/60">
            <div class="md:col-span-5">
                <x-w-input label="Descripción de la medida" type="text" x-model="tempDescription"
                    placeholder="Ej: Metro lineal (50cm ancho)" @keydown.enter.prevent="addPrice" />
            </div>
            <div class="md:col-span-4">
                <x-w-input label="Precio ($)" type="number" x-model="tempPrice" step="0.01" min="0"
                    placeholder="Ej: 85.00" @keydown.enter.prevent="addPrice" />
            </div>
            <div class="md:col-span-3 w-full">
                <x-w-button type="button" @click="addPrice" icon="plus" label="Añadir" blue
                    class="w-full shadow-sm" />
            </div>
        </div>

        <div class="mt-8">
            <h3
                class="text-xs font-bold text-gray-500 dark:text-gray-400 mb-4 uppercase tracking-wider flex items-center gap-2">
                <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                    stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M8.25 6.75h12M8.25 12h12m-12 5.25h12M3.75 6.75h.007v.008H3.75V6.75zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zM3.75 12h.007v.008H3.75V12zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm-.375 5.25h.007v.008H3.75v-.008zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                </svg>
                Tarifas Registradas
            </h3>

            <div class="space-y-3">
                <template x-for="(item, index) in items" :key="index">
                    <div x-transition.duration.300ms
                        class="group flex flex-col sm:flex-row justify-between sm:items-center gap-4 bg-white dark:bg-gray-800 p-4 rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm hover:border-blue-200 dark:hover:border-blue-900 transition-all">

                        <div class="flex items-center gap-4">
                            <span
                                class="flex-shrink-0 flex items-center justify-center w-8 h-8 rounded-full bg-slate-100 text-slate-600 dark:bg-slate-700 dark:text-slate-300 text-sm font-bold"
                                x-text="index + 1"></span>

                            <div class="flex flex-col">
                                <span class="font-semibold text-gray-800 dark:text-gray-200 text-base"
                                    x-text="item.description"></span>
                                <span class="text-xs text-gray-500 dark:text-gray-400">Medida configurada</span>
                            </div>
                        </div>

                        <div
                            class="flex items-center justify-between sm:justify-end gap-4 w-full sm:w-auto border-t sm:border-0 border-gray-100 dark:border-gray-700 pt-3 sm:pt-0">
                            <span
                                class="bg-emerald-50 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-400 px-3 py-1.5 rounded-lg font-bold tracking-wide border border-emerald-200 dark:border-emerald-800/50">
                                $ <span x-text="Number(item.price).toFixed(2)"></span>
                            </span>

                            <x-w-button @click="removePrice(index)" type="button" red icon="trash"
                                class="opacity-100 sm:opacity-40 group-hover:opacity-100 transition-opacity" />
                        </div>
                    </div>
                </template>
            </div>

            <div x-show="items.length === 0" x-transition.opacity
                class="flex flex-col items-center justify-center py-10 px-4 bg-slate-50 dark:bg-gray-800/30 rounded-xl border-2 border-dashed border-slate-300 dark:border-gray-700 mt-2">
                <div class="bg-white dark:bg-gray-700 p-3 rounded-full mb-3 shadow-sm">
                    <svg class="w-6 h-6 text-slate-400 dark:text-gray-400" xmlns="http://www.w3.org/2000/svg"
                        fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M2.25 18.75a60.07 60.07 0 0 1 15.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 0 1 3 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 0 0-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 0 1-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 0 0 3 15h-.75M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm3 0h.008v.008H18V10.5Zm-12 0h.008v.008H6V10.5Z" />
                    </svg>
                </div>
                <p class="text-slate-700 dark:text-gray-300 font-medium text-sm">Sin tarifas configuradas</p>
                <p class="text-slate-500 dark:text-gray-500 text-xs mt-1 text-center max-w-sm">Registra las medidas y
                    el precio usando el formulario de arriba.</p>
            </div>
        </div>
    </section>

    <div class="pt-6 mt-6 border-t border-gray-100 dark:border-gray-800 flex justify-end">
        <x-w-button wire:click="saveVinil" label="Guardar Configuración" icon="cloud-arrow-up" blue
            class="w-full sm:w-auto px-8 py-2.5 shadow-md hover:shadow-lg transition-all" />
    </div>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('priceManager', (params) => ({
            // Sincronización bidireccional
            items: @entangle('prices'),
            tempDescription: '',
            tempPrice: '',

            init() {
                if (this.items.length === 0 && params.initialPrices.length > 0) {
                    this.items = params.initialPrices;
                }
            },

            addPrice() {
                if (this.tempDescription.trim() === '' || this.tempPrice === '') return;

                this.items.push({
                    description: this.tempDescription.trim(),
                    price: parseFloat(this.tempPrice)
                });

                this.tempDescription = '';
                this.tempPrice = '';
            },

            removePrice(index) {
                this.items.splice(index, 1);
            }
        }));
    });
</script>

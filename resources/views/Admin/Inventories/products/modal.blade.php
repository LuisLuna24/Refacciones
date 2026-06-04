<x-w-modal-card title="Stock por almacén" wire:model="openModal">
    <ul class="space-y-3">
        @forelse ($inventories as $inventory)
            @php
                // Determinamos el color base según el balance
                $isPositive = $inventory->quantity_balance > 0;
                $color = $isPositive ? 'emerald' : 'red';
            @endphp

            <li
                class="flex items-center justify-between p-4 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl shadow-sm hover:shadow-md transition-all duration-200">

                <div class="flex items-center gap-3">
                    <div
                        class="p-2 rounded-lg bg-{{ $color }}-50 text-{{ $color }}-600 dark:bg-{{ $color }}-900/30 dark:text-{{ $color }}-400">
                        {!! file_get_contents(public_path('svg/building-warehouse.svg')) !!}
                    </div>

                    <div>
                        <h4 class="font-semibold text-gray-900 dark:text-gray-100">
                            {{ $inventory->warehouse->name }}
                        </h4>

                        <p class="text-xs text-gray-500 dark:text-gray-400 flex items-center gap-1">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            {{ $inventory->warehouse->location }}
                        </p>
                    </div>
                </div>

                <div class="text-right">
                    <p class="text-xs font-medium text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-1">
                        Disponible
                    </p>
                    <p class="text-2xl font-bold text-{{ $color }}-600 dark:text-{{ $color }}-400">
                        {{ $inventory->quantity_balance }}
                    </p>
                </div>
            </li>
        @empty
            <li
                class="flex flex-col items-center justify-center p-8 text-center bg-gray-50 dark:bg-gray-800/50 rounded-lg border border-dashed border-gray-300 dark:border-gray-600">
                <svg class="w-10 h-10 text-gray-400 dark:text-gray-500 mb-2" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                </svg>
                <p class="text-gray-500 dark:text-gray-400 font-medium">No hay registros de inventario</p>
            </li>
        @endforelse
    </ul>
</x-w-modal-card>

<x-w-modal-card title="Tags" wire:model="tagsModal">
    <div x-data="{
        search: '',
        selectedTags: @entangle('selectedTags'),
        newTags: @entangle('newTags'),
        allTags: {{ \App\Models\Tag::orderBy('name')->get()->map(fn($t) => ['id' => (string)$t->id, 'name' => $t->name])->toJson() }},
        
        get filteredTags() {
            if (this.search.trim() === '') {
                return this.allTags;
            }
            return this.allTags.filter(tag => tag.name.toLowerCase().includes(this.search.toLowerCase()));
        },
        
        addTag() {
            const val = this.search.trim();
            if (val === '') return;
            
            const existing = this.allTags.find(tag => tag.name.toLowerCase() === val.toLowerCase());
            
            if (existing) {
                if (!this.selectedTags.includes(existing.id)) {
                    this.selectedTags.push(existing.id);
                }
            } else {
                if (!this.newTags.includes(val)) {
                    this.newTags.push(val);
                }
            }
            this.search = '';
        },
        
        removeNewTag(tagToRemove) {
            this.newTags = this.newTags.filter(t => t !== tagToRemove);
        }
    }">
    
        <div class="flex items-center gap-2">
            <div class="flex-1">
                <x-w-input 
                    label="Buscar o crear tag" 
                    placeholder="Escribe y presiona Enter..."
                    x-model="search" 
                    @keydown.enter.prevent="addTag"
                />
            </div>
            <div class="mt-6">
                <x-w-button @click="addTag" primary>Agregar</x-w-button>
            </div>
        </div>

        <div x-show="newTags.length > 0" class="mt-4" style="display: none;">
            <h5 class="text-xs font-semibold text-gray-500 uppercase">Nuevos por crear:</h5>
            <div class="flex flex-wrap gap-2 mt-2">
                <template x-for="nTag in newTags" :key="nTag">
                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium bg-primary-100 text-primary-800 dark:bg-primary-900/30 dark:text-primary-400 border border-primary-200 dark:border-primary-800">
                        <span x-text="nTag"></span>
                        <button type="button" @click="removeNewTag(nTag)" class="hover:text-primary-900 dark:hover:text-primary-200">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </span>
                </template>
            </div>
        </div>

        <div class="mt-6 border-t border-gray-200 dark:border-gray-700 pt-4">
            <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Tags Existentes</h4>
            
            <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                <template x-for="tag in filteredTags" :key="tag.id">
                    <div class="flex items-center">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input 
                                type="checkbox" 
                                x-model="selectedTags" 
                                :value="tag.id" 
                                class="rounded border-gray-300 text-primary-600 shadow-sm focus:border-primary-300 focus:ring focus:ring-primary-200 focus:ring-opacity-50 dark:border-gray-600 dark:bg-gray-800 dark:focus:ring-offset-gray-900"
                            />
                            <span class="text-sm text-gray-700 dark:text-gray-300" x-text="tag.name"></span>
                        </label>
                    </div>
                </template>
            </div>

            <div x-show="filteredTags.length === 0" style="display: none;">
                <p class="text-center text-sm text-gray-500 py-4">
                    No se encontraron coincidencias. Presiona enter para agregarlo como nuevo.
                </p>
            </div>
        </div>
    </div>
    
    <x-slot name="footer">
        <div class="flex justify-end gap-x-4">
            <x-w-button flat label="Cancelar" wire:click="$set('tagsModal', false)" />
            <x-w-button primary label="Guardar Tags" wire:click="saveTags" />
        </div>
    </x-slot>
</x-w-modal-card>

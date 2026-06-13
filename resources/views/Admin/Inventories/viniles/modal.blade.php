<x-w-modal-card title="Precios" wire:model="priceModal">

    {{-- Listado de precios con soporte para Modo Oscuro --}}
    <div class="space-y-3">
        @forelse ($prices as $price)
            <div
                class="flex justify-between items-center p-3 bg-gray-50 dark:bg-zinc-800 rounded-lg border border-gray-100 dark:border-zinc-700 transition-colors">
                <div>
                    <span class="font-medium text-gray-700 dark:text-zinc-200">
                        {{ $price->name ?? 'Precio Base' }}
                    </span>
                    <span class="text-xs text-gray-400 dark:text-zinc-500 block">
                        Unidad: {{ $unidadType }}
                    </span>
                </div>
                <span class="text-emerald-600 dark:text-emerald-400 font-semibold text-lg">
                    ${{ number_format($price->price, 2) }}
                </span>
            </div>
        @empty
            <div class="text-center py-6 text-gray-500 dark:text-zinc-400">
                <p class="text-sm">No hay precios configurados para este vinilo.</p>
            </div>
        @endforelse
    </div>

    {{-- Pie del modal --}}
    <x-slot name="footer">
        <div class="flex justify-end gap-x-4">
            <x-w-button label="Cerrar" wire:click="$set('priceModal', false)" />
        </div>
    </x-slot>

</x-w-modal-card>

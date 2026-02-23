<div class="flex items-center space-x-2">
    @can('edit-delivery-notes')

        {{-- Si la nota está Cancelada (0), ocultamos todas las acciones --}}
        @if ($deliveryNote->status !== 0)
            {{-- Botón de Editar (Visible para Pendiente, Pagado y Entregado) --}}
            <x-w-button href="{{ route('admin.delivery_notes.edit', $deliveryNote) }}" icon="pencil" blue xs>
                Editar
            </x-w-button>

            {{-- Acciones rápidas (Solo visibles si NO está Entregada) --}}
            @if ($deliveryNote->status !== 3)
                <x-w-button wire:click="noteDelivery({{ $deliveryNote->id }})" icon="check" green xs>
                    Entregado
                </x-w-button>

                <x-w-button wire:click="noteCancel({{ $deliveryNote->id }})" icon="x" red xs>
                    Cancelar
                </x-w-button>
            @endif
        @endif

    @endcan
</div>

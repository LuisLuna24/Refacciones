<div class="flex items-center space-x-2">
    @can('edit-delivery-notes')

        {{-- Si la nota está Cancelada (0), ocultamos todas las acciones --}}
        @if ($deliveryNote->status !== 0)
            {{-- Botón de Editar (Visible para Pendiente, Pagado y Entregado) --}}
            <x-w-button href="{{ route('admin.delivery_notes.edit', $deliveryNote) }}" icon="pencil" blue spinner />
            <x-w-button href="{{ route('admin.delivery_notes.pdf', $deliveryNote) }}" icon="document-arrow-down" gray spinner />

            {{-- Acciones rápidas (Solo visibles si NO está Entregada) --}}
            @if ($deliveryNote->status !== 3)
                <x-w-button wire:click="noteDelivery({{ $deliveryNote->id }})" green icon="check-badge" spinner />

                <x-w-button wire:click="noteCancel({{ $deliveryNote->id }})" red icon="x-circle" interaction="negative"
                    spinner />
            @endif
        @endif

    @endcan
</div>

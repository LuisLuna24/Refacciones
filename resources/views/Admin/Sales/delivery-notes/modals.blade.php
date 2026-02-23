<x-modal wire:model.defer="modalNoteDelivery" align="center" blur="sm">
    <x-w-card title="Confirmar Entrega">

        <div class="py-4 text-center">
            <div
                class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-green-100 dark:bg-green-900/50 mb-4">
                <svg class="h-6 w-6 text-green-600 dark:text-green-400" fill="none" viewBox="0 0 24 24" stroke-width="2"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>

            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">
                ¿Marcar pedido como entregado?
            </h3>
            <p class="text-sm text-gray-500 dark:text-gray-400">
                Al confirmar, el estado de esta nota de entrega pasará a "Entregado" y ya no aparecerá como pendiente.
            </p>
        </div>

        <x-slot name="footer">
            <div class="flex justify-end gap-x-3">
                <x-w-button flat label="Cancelar" wire:click="closeModal" class="text-gray-600 dark:text-gray-400" />

                <x-w-button positive label="Sí, marcar como entregado" wire:click="saveNoteDelivery"
                    spinner="saveNoteDelivery" />
            </div>
        </x-slot>

    </x-w-card>
</x-modal>

<x-modal wire:model.defer="modalCancelDelivery" align="center" blur="sm">
    <x-w-card title="Cancelar Nota de Entrega">

        <div class="py-4 text-center">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 dark:bg-red-900/50 mb-4">
                <svg class="h-6 w-6 text-red-600 dark:text-red-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </div>

            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">
                ¿Estás seguro de cancelar esta nota?
            </h3>
            <p class="text-sm text-gray-500 dark:text-gray-400 px-4">
                El estado de la nota pasará a "Cancelado" (0) y los totales ya no se sumarán a tus ingresos. Esta acción no se puede deshacer fácilmente.
            </p>
        </div>

        <x-slot name="footer">
            <div class="flex justify-end gap-x-3">
                <x-w-button
                    flat
                    label="No, mantener nota"
                    wire:click="closeCancelModal"
                    class="text-gray-600 dark:text-gray-400"
                />

                <x-w-button
                    negative
                    label="Sí, cancelar nota"
                    wire:click="saveNoteDelivery"
                    spinner="saveNoteDelivery"
                />
            </div>
        </x-slot>

    </x-w-card>
</x-modal>

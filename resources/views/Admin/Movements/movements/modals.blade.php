<x-modal wire:model.defer="modalProductsSale" align="center" blur="sm" max-width="2xl">
    <x-w-card title="Detalle de Productos">

        <div class="py-2">
            <div class="max-h-96 overflow-y-auto overflow-x-auto rounded-lg border border-gray-200 dark:border-gray-700">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 relative">

                    <thead class="bg-gray-50 dark:bg-gray-800 sticky top-0 z-10 shadow-sm">
                        <tr>
                            <th scope="col"
                                class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Producto</th>
                            <th scope="col"
                                class="px-4 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Cantidad</th>
                            <th scope="col"
                                class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Precio</th>
                            <th scope="col"
                                class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Subtotal</th>
                        </tr>
                    </thead>

                    <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-900 dark:divide-gray-700">
                        @forelse($prouctsSale as $product)
                            <tr>
                                <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100">
                                    {{ $product->name ?? 'Nombre del producto' }}
                                </td>
                                <td class="px-4 py-3 text-sm text-center text-gray-900 dark:text-gray-100">
                                    {{ $product->quantity ?? ($product->pivot->quantity ?? 1) }}
                                </td>
                                <td class="px-4 py-3 text-sm text-right text-gray-900 dark:text-gray-100">
                                    ${{ number_format($product->price ?? 0, 2) }}
                                </td>
                                <td class="px-4 py-3 text-sm text-right font-medium text-gray-900 dark:text-gray-100">
                                    ${{ number_format(($product->price ?? 0) * ($product->quantity ?? ($product->pivot->quantity ?? 1)), 2) }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4"
                                    class="px-4 py-6 text-center text-sm text-gray-500 dark:text-gray-400">
                                    No hay productos para mostrar en esta venta.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <x-slot name="footer">
            <div class="flex justify-end gap-x-3">
                <x-w-button flat label="Cerrar" wire:click="closeModalProducts"
                    class="text-gray-600 dark:text-gray-400" />
            </div>
        </x-slot>
    </x-w-card>
</x-modal>

@include('Admin.Pdf.modal')

<?php

namespace App\Livewire\Admin\Datatables\Sales;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\DeliveryNote;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class DeliveryNoteTable extends DataTableComponent
{
    protected $model = DeliveryNote::class;

    public function configure(): void
    {
        $this->setPrimaryKey('id');

        $this->setConfigurableAreas([
            'after-wrapper' => 'Admin.Sales.delivery-notes.modals',
        ]);
    }

    public function columns(): array
    {
        return [
            Column::make("Id", "id")
                ->sortable(),
            Column::make("Nro Comprobante")
                ->label(
                    fn($row) => $row->serie . '-' . $row->correlative
                )
                ->searchable(function (Builder $query, $term) {
                    return $query->orWhereRaw('CONCAT(serie, "-", correlative) LIKE ?', ["%{$term}%"]);
                }),

            Column::make("Serie", "serie")
                ->sortable()
                ->deselected(),

            Column::make("Correlative", "correlative")
                ->sortable()
                ->deselected(),
            Column::make("Fecha", "date")
                ->sortable()
                ->format(fn($value) => $value->format('Y-m-d')),
            Column::make("Total", "total")
                ->sortable()
                ->format(fn($value) => '$ ' . number_format($value, 2, '.', ',')),
            Column::make("Estatus", 'status')
                ->format(fn($value, $row, $column) => match ((int) $row->status) {
                    0 => '<span class="px-2 py-1 text-xs font-semibold text-red-800 bg-red-200 rounded-full dark:bg-red-900 dark:text-red-300">Cancelado</span>',
                    1 => '<span class="px-2 py-1 text-xs font-semibold text-yellow-800 bg-yellow-200 rounded-full dark:bg-yellow-900 dark:text-yellow-300">Pendiente</span>',
                    2 => '<span class="px-2 py-1 text-xs font-semibold text-blue-800 bg-blue-200 rounded-full dark:bg-blue-900 dark:text-blue-300">Pagado</span>',
                    3 => '<span class="px-2 py-1 text-xs font-semibold text-green-800 bg-green-200 rounded-full dark:bg-green-900 dark:text-green-300">Entregado</span>',
                    default => '<span class="px-2 py-1 text-xs font-semibold text-gray-800 bg-gray-200 rounded-full dark:bg-gray-700 dark:text-gray-300">Desconocido</span>',
                })
                ->html() // No olvides esto para que se vean los colores y no el código HTML
                ->sortable(),
            Column::make("Acciones")
                ->label(function ($row) {
                    return view('Admin.Sales.delivery-notes.actions', ['deliveryNote' => $row]);
                })
        ];
    }

    public $modalNoteDelivery = false;
    public $deliveryNoteId;
    public $newStatus;

    public function noteDelivery($id)
    {
        $this->modalNoteDelivery = true;
        $this->deliveryNoteId = $id;
        $this->newStatus = 3;
    }

    public $modalCancelDelivery = false;

    public function noteCancel($id)
    {
        $this->modalCancelDelivery = true;
        $this->deliveryNoteId = $id;
        $this->newStatus = 0;
    }



    public function saveNoteDelivery()
    {
        DB::beginTransaction();
        try {
            $delivery = DeliveryNote::findOrFail($this->deliveryNoteId);

            $delivery->update([
                'status' => $this->newStatus
            ]);

            DB::commit();

            if ($this->newStatus == 3) {
                $this->dispatch('swal', [
                    'icon' => 'success',
                    'title' => 'Éxito',
                    'text' => 'Nota entregada correctamente'
                ]);
            } elseif ($this->newStatus == 0) {
                $this->dispatch('swal', [
                    'icon' => 'success',
                    'title' => 'Éxito',
                    'text' => 'Nota cancelada correctamente'
                ]);
            }

            $this->reset(['modalCancelDelivery', 'modalNoteDelivery', 'newStatus', 'deliveryNoteId']);
        } catch (\Exception $e) {
            DB::rollBack();

            $this->dispatch('swal', [
                'icon' => 'error',
                'title' => 'Error',
                'text' => 'Lo sentimos, ha ocurrido un error inesperado.'
            ]);
        }
    }
}

<?php

namespace App\Livewire\Admin\Datatables\Inventories;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Illuminate\Database\Eloquent\Builder;
use App\Models\VinilType;

class VinilTable extends DataTableComponent
{
    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setDefaultSort('id', 'desc');

        $this->setConfigurableAreas([
            'after-wrapper' => [
                'Admin.Inventories.viniles.modal',
            ]
        ]);
    }

    public function columns(): array
    {
        return [
            Column::make("Id", "id")
                ->sortable(),
            Column::make("Nombre", "name")
                ->searchable()
                ->sortable(),
            Column::make("Presion (g)", "pressure")
                ->sortable(),
            Column::make("Unity", "unity")
                ->deselected(),
            Column::make("Precio default", "price_default")
                ->deselected(),
            Column::make("Precios", "pricces")
                ->label(function ($row) {
                    return view('Admin.Inventories.viniles.prices', ['vinilType' => $row]);
                }),
            Column::make("Acciones")
                ->label(function ($row) {
                    return view('Admin.Inventories.viniles.actions', ['vinilType' => $row]);
                })
        ];
    }

    public function builder(): Builder
    {
        return VinilType::query()
            ->with(['prices']);
    }

    public $priceModal = false;
    public $prices = [];
    public $unidadType = '';

    public function showPrices($id)
    {
        $this->priceModal = true;
        $vinilType = VinilType::find($id);
        $this->unidadType = $vinilType->unity;
        $this->prices = $vinilType->prices;
    }
}

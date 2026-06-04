<?php

namespace App\Livewire\Admin\Datatables\Inventories;

use App\Models\Inventory;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\Product;
use App\Models\Tag;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Rappasoft\LaravelLivewireTables\Views\Columns\ImageColumn;

class ProductTable extends DataTableComponent
{
    //protected $model = Product::class;

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setDefaultSort('id', 'desc');

        $this->setConfigurableAreas([
            'after-wrapper' => [
                'Admin.Inventories.products.modal',
            ]
        ]);
    }

    public function columns(): array
    {
        return [
            Column::make("Sku", "sku")
                ->searchable()
                ->sortable(),
            Column::make("Id", "id")
                ->deselected(),
            ImageColumn::make("Img")
                ->location(
                    fn($row) => $row->image
                )->attributes(
                    fn($row) => [
                        'class' => 'image-product'
                    ]
                ),
            Column::make("Nombre", "name")
                ->searchable()
                ->sortable(),
            Column::make("Categoría", 'category.name')
                ->sortable(),
            Column::make("Precio", "price")
                ->sortable(),
            Column::make("Costo", "cost")
                ->sortable(),
            Column::make("Tags", "tags")
                ->label(function ($row) {
                    return view('Admin.Inventories.products.tags', ['product' => $row]);
                }),
            Column::make("Stock", 'stock')
                ->sortable()
                ->format(function ($value, $row) {
                    return view('Admin.Inventories.products.stock', ['stock' => $value, 'product' => $row]);
                }),
            Column::make("Acciones")
                ->label(function ($row) {
                    return view('Admin.Inventories.products.actions', ['product' => $row]);
                })
        ];
    }

    public function builder(): Builder
    {

        return Product::query()
            ->with(['category', 'images', 'tags']);
    }

    //================Propiedades

    public $openModal = false;

    public $inventories = [];

    //================Metodos

    public function showStock($productId)
    {
        $this->openModal = true;

        $latestInventories = Inventory::where('product_id', $productId)
            ->select('warehouse_id', DB::raw('MAX(id) as id'))
            ->groupBy('warehouse_id')
            ->pluck('id');

        $this->inventories = Inventory::whereIn('id', $latestInventories)
            ->with(['warehouse'])
            ->get();
    }

    //================Tags

    public $tagsModal = false;

    public ?Product $tagsProduct = null;
    public $selectedTags = [];
    public $newTags = [];

    public function showTags($productId)
    {
        $this->reset('selectedTags', 'newTags', 'tagsProduct');
        $this->tagsProduct = Product::find($productId);
        $this->selectedTags = $this->tagsProduct->tags->pluck('id')->map(fn($id) => (string) $id)->toArray();
        $this->newTags = [];
        $this->tagsModal = true;
    }

    public function saveTags()
    {
        if ($this->tagsProduct) {
            $createdIds = [];

            // Crear los nuevos tags que vienen de Alpine
            foreach ($this->newTags as $tagName) {
                if (trim($tagName) !== '') {
                    $tag = Tag::firstOrCreate(['name' => trim($tagName)]);
                    $createdIds[] = (string) $tag->id;
                }
            }

            // Unir tags existentes seleccionados con los recién creados
            $finalTagIds = array_unique(array_merge($this->selectedTags, $createdIds));

            $this->tagsProduct->tags()->sync($finalTagIds);
            $this->tagsProduct->load('tags');

            $this->tagsModal = false;
        }
    }
}

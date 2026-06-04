<?php

namespace App\Livewire\Home\Refacciones;

use App\Models\Category;
use App\Models\Product;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Url;

class Index extends Component
{
    use WithPagination;

    #[Url(except: '')]
    public $search = '';

    #[Url(except: '')]
    public $category_id = '';

    public $categories = [];

    public function mount()
    {
        // Optimización extra: Si tu tabla de categorías es grande, es mejor
        // traer solo las columnas que vas a usar en tu <select> (ej. id y name).
        $this->categories = Category::select('id', 'name')->get();
    }

    // Resetear paginación si se busca o filtra
    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedCategoryId()
    {
        $this->resetPage();
    }

    public function render()
    {
        // 1. Evitar N+1: Agregamos 'tags' al Eager Loading.
        $query = Product::query()
            ->with(['category', 'images', 'tags']);

        // 2. Filtro por Buscador (Nombre, Descripción o Tags)
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('description', 'like', '%' . $this->search . '%')
                    // Agregamos la búsqueda por el nombre del tag relacionado
                    ->orWhereHas('tags', function ($tagQuery) {
                        // Asumo que la columna en tu tabla de tags se llama 'name'
                        $tagQuery->where('name', 'like', '%' . $this->search . '%');
                    });
            });
        }

        // Filtro por Categoría
        if ($this->category_id) {
            $query->where('category_id', $this->category_id);
        }

        // Opcional: Solo mostrar productos con stock
        // $query->where('stock', '>', 0);

        $products = $query->paginate(9); // 9 productos por página

        return view('livewire.home.refacciones.index', [
            'products' => $products,
        ]);
    }
}

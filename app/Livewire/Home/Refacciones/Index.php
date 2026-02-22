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
        $this->categories = Category::all();
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
        // Consulta base con Eager Loading para optimizar (evitar N+1)
        $query = Product::query()
            ->with(['category', 'images']);

        // Filtro por Buscador (Nombre o Descripción)
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('description', 'like', '%' . $this->search . '%');
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

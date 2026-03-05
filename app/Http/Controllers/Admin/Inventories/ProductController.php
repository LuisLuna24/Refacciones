<?php

namespace App\Http\Controllers\Admin\Inventories;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        Gate::authorize('view-products');
        return view("Admin.Inventories.products.index");
    }

    public function create()
    {
        Gate::authorize('create-products');
        return view("Admin.Inventories.products.create");
    }

    public function edit(Product $product)
    {
        Gate::authorize('edit-products');
        return view("Admin.Inventories.products.edit", compact('product'));
    }

    public function destroy(Product $product)
    {
        Gate::authorize('delete-products');
        $checkRelations = [
            'inventories'    => 'inventarios asociados',
            'purchaseOrders' => 'órdenes asociadas',
            'quotes'         => 'cotizaciones asociadas',
        ];

        foreach ($checkRelations as $relation => $message) {
            if ($product->$relation()->exists()) {
                Session::flash('swal', [
                    'icon'  => 'error',
                    'title' => '¡Error!',
                    'text'  => "No se puede eliminar el producto porque tiene $message.",
                ]);

                return redirect()->back();
            }
        }

        $product->delete();

        Session::flash('swal', [
            'icon'  => 'success',
            'title' => '¡Eliminado con éxito!',
            'text'  => 'El producto se ha eliminado con éxito.',
        ]);

        return redirect()->route('admin.products.index');
    }

    public function dropzone(Request $request, Product $product)
    {
        $image = $product->images()->create([
            'path' => Storage::put('/images', $request->file('file')),
            'size' => $request->file('file')->getSize(),
        ]);

        return response()->json([
            'id' => $image->id,
            'path' => $image->path,
        ]);
    }

    public function Kardex(Product $product)
    {
        return view('Admin.Inventories.products.Kardex', compact('product'));
    }

    public function show()
    {
        return view('Admin.Inventories.products.import');
    }

    public function import()
    {
        return view('Admin.Inventories.products.import');
    }
}

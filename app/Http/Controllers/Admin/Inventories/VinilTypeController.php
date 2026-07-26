<?php

namespace App\Http\Controllers\Admin\Inventories;

use App\Http\Controllers\Controller;
use App\Models\VinilType;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Session;

class VinilTypeController extends Controller
{
    public function index()
    {
        Gate::authorize('view-viniles');
        return view("Admin.Inventories.viniles.index");
    }

    public function create()
    {
        Gate::authorize('create-viniles');
        return view("Admin.Inventories.viniles.create");
    }

    public function edit(VinilType $vinilType)
    {
        Gate::authorize('edit-viniles');
        return view("Admin.Inventories.viniles.edit", compact('vinilType'));
    }

    public function destroy(VinilType $vinilType)
    {
        Gate::authorize('delete-viniles');

        $vinilType->delete();

        Session::flash('swal', [
            'icon'  => 'success',
            'title' => '¡Eliminado con éxito!',
            'text'  => 'El vinil se ha eliminado con éxito.',
        ]);

        return redirect()->route('admin.viniles.index');
    }
}

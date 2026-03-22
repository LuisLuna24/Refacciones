<?php

namespace App\Http\Controllers\admin\users;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Session;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public function index()
    {
        Gate::authorize('view-roles');
        return view('Admin.Users.roles.index');
    }

    public function create()
    {
        Gate::authorize('create-roles');
        return view('Admin.Users.roles.create');
    }

    public function edit(Role $role)
    {
        Gate::authorize('edit-roles');
        return view('Admin.Users.roles.edit', [
            'role' => $role,
        ]);
    }

    public function destroy(Role $role)
    {
        try {
            // 1. Autorización
            Gate::authorize('delete-roles');

            // 2. Validación de restricción (Nota el plural 'users')
            // Usamos exists() en lugar de count() > 0 porque es más eficiente en BD
            if ($role->users()->exists()) {
                Session::flash('swal', [
                    'icon'  => 'error',
                    'title' => '¡No permitido!',
                    'text'  => 'No se puede eliminar el rol porque tiene usuarios asociados.',
                ]);
                return null;
            }

            // 3. Eliminación
            $role->delete();

            session()->flash('swal', [
                'icon'  => 'success',
                'title' => '¡Eliminado!',
                'text'  => 'El rol ha sido eliminado correctamente.',
            ]);
        } catch (\Exception $e) {
            Session::flash('swal', [
                'icon'  => 'error',
                'title' => '¡Error!',
                'text'  => 'Ocurrió un error inesperado: ' . $e->getMessage(),
            ]);
        }

        return redirect()->route('admin.roles.index');
    }
}

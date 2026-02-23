<?php

namespace App\Http\Controllers\Admin\Sales;

use App\Http\Controllers\Controller;
use App\Models\DeliveryNote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class DeliveryNoteController extends Controller
{
    public function index()
    {
        Gate::authorize('view-delivery-notes');
        return view("Admin.Sales.delivery-notes.index");
    }
    public function create()
    {
        Gate::authorize('create-delivery-notes');
        return view("Admin.Sales.delivery-notes.create");
    }
    public function edit(DeliveryNote $deliveryNote)
    {
        Gate::authorize('edit-delivery-notes');
        return view("Admin.Sales.delivery-notes.edit", compact("deliveryNote"));
    }
}

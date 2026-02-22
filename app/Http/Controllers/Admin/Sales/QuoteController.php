<?php

namespace App\Http\Controllers\Admin\Sales;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Quote;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class QuoteController extends Controller
{

    public function index()
    {
        Gate::authorize('view-quotes');
        return view("Admin.Sales.quotes.index");
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        Gate::authorize('create-quotes');
        return view("Admin.Sales.quotes.create");
    }

    public function pdf(Quote $quote)
    {
        $pdf = Pdf::loadView('admin.sales.quotes.pdf', [
            'model' => $quote,
        ]);

        return $pdf->download("cotizacion_{$quote->id}.pdf");
    }
}

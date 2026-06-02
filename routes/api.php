<?php

use App\Models\Category;
use App\Models\Customer;
use App\Models\Product;
use App\Models\PurchaseOrder;
use App\Models\Quote;
use App\Models\Reason;
use App\Models\Supplier;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/suppliers', function (Request $request) {
    return Supplier::select('id', 'name')
        ->when($request->input('search'), function ($query, $search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('document_number', 'like', '%' . $search . '%');
            });
        })
        ->when(
            $request->filled('selected'),
            fn ($query) => $query->whereIn('id', $request->input('selected', [])),
            fn ($query) => $query->limit(10)
        )
        ->orderBy('name')
        ->get();
})->name('api.suppliers.index');

Route::get('/customers', function (Request $request) {
    return Customer::select('id', 'name')
        ->when($request->input('search'), function ($query, $search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('document_number', 'like', '%' . $search . '%');
            });
        })
        ->when(
            $request->filled('selected'),
            fn ($query) => $query->whereIn('id', $request->input('selected', [])),
            fn ($query) => $query->limit(10)
        )
        ->orderBy('name')
        ->get();
})->name('api.customers.index');

Route::get('/products/notes', function (Request $request) {
    return Product::select('id', 'name')
        ->when($request->input('search'), function ($query, $search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('barcode', 'like', '%' . $search . '%');
            });
        })
        ->limit(10) // No necesitas un 'when' si el límite aplica siempre que no hay 'selected' (ajustado para ser consistente, asumiendo que no necesitas selected aquí)
        ->orderBy('name')
        ->get();
})->name('api.productsnotes.index');

Route::get('/purchase-orders', function (Request $request) {
    $purchaseOrders = PurchaseOrder::query()
        ->when($request->input('search'), function ($query, $search) {
            $parts = explode('-', $search);

            if (count($parts) == 1) {
                $query->whereHas('supplier', function ($q) use ($search) {
                    $q->where('name', 'LIKE', '%' . $search . '%')
                      ->orWhere('document_number', 'LIKE', '%' . $search . '%');
                });
            } elseif (count($parts) == 2) {
                $serie = $parts[0];
                $correlative = ltrim($parts[1], '0');

                $query->where('serie', $serie)
                      ->where('correlative', 'LIKE', "%{$correlative}%");
            }
        })
        ->when(
            $request->filled('selected'),
            fn ($query) => $query->whereIn('id', $request->input('selected', [])),
            fn ($query) => $query->limit(10)
        )
        ->where('status', 0)
        ->with('supplier')
        ->orderByDesc('id')
        ->get();

    // CORRECCIÓN: Se cambió el parámetro a $order para evitar variables duplicadas
    return $purchaseOrders->map(function ($order) {
        return [
            'id' => $order->id,
            'name' => $order->serie . '-' . $order->correlative,
            'description' => $order->supplier ? $order->supplier->name . '-' . $order->supplier->document_number : 'Sin proveedor',
        ];
    });
})->name('api.purchase-orders.index');

Route::get('/warehouses', function (Request $request) {
    return Warehouse::select('id', 'name', 'location as description')
        ->when($request->input('search'), function ($query, $search) {
            $query->where('name', 'like', '%' . $search . '%');
        })
        ->when($request->filled('exclude'), function ($query) use ($request) {
            $query->where('id', '!=', $request->input('exclude'));
        })
        ->when(
            $request->filled('selected'),
            fn ($query) => $query->whereIn('id', $request->input('selected', [])),
            fn ($query) => $query->limit(10)
        )
        ->orderBy('name')
        ->get();
})->name('api.warehouses.index');

Route::get('/quotes', function (Request $request) {
    $quotes = Quote::query()
        ->when($request->input('search'), function ($query, $search) {
            $query->where(function ($subQuery) use ($search) {
                $parts = explode('-', $search);

                if (count($parts) == 1) {
                    $subQuery->whereHas('customer', function ($q) use ($search) {
                        $q->where('name', 'LIKE', '%' . $search . '%')
                          ->orWhere('document_number', 'LIKE', '%' . $search . '%');
                    });
                } elseif (count($parts) == 2) {
                    $serie = $parts[0];
                    $correlative = ltrim($parts[1], '0');

                    $subQuery->where('serie', $serie)
                             ->where('correlative', 'LIKE', "%{$correlative}%");
                }
            });
        })
        ->when(
            $request->filled('selected'),
            fn ($query) => $query->whereIn('id', $request->input('selected', [])),
            fn ($query) => $query->limit(10)
        )
        ->where('status', 0)
        ->with('customer')
        ->orderByDesc('id')
        ->get();

    return $quotes->map(function ($quote) {
        return [
            'id' => $quote->id,
            'name' => $quote->serie . '-' . $quote->correlative,
            'description' => $quote->customer
                ? $quote->customer->name . ' - ' . $quote->customer->document_number
                : null,
        ];
    });
})->name('api.quotes.index');

Route::get('/reasons', function (Request $request) {
    return Reason::select('id', 'name')
        ->when($request->input('search'), function ($query, $search) {
            $query->where('name', 'like', '%' . $search . '%');
        })
        ->when(
            $request->filled('selected'),
            fn ($query) => $query->whereIn('id', $request->input('selected', [])),
            fn ($query) => $query->limit(10)
        )
        // CORRECCIÓN: Solo filtra por tipo si realmente se envía en el request
        ->when($request->filled('type'), function ($query) use ($request) {
            $query->where('type', $request->input('type'));
        })
        ->orderBy('name')
        ->get();
})->name('api.reasons.index');

Route::get('/categories', function (Request $request) {
    return Category::select('id', 'name')
        ->when($request->input('search'), function ($query, $search) {
            $query->where('name', 'like', '%' . $search . '%');
        })
        ->when(
            $request->filled('selected'),
            fn ($query) => $query->whereIn('id', $request->input('selected', [])),
            fn ($query) => $query->limit(10)
        )
        ->orderBy('name')
        ->get();
})->name('api.categories.index');
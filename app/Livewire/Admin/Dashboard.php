<?php

namespace App\Livewire\Admin;

use App\Models\Product;
use App\Models\Sale;
use Carbon\Carbon;
use Livewire\Component;

class Dashboard extends Component
{
    public function render()
    {
        $today = Carbon::today();
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        // 1. Ganancias del Día (Suma del total de ventas de hoy)
        // Se asume que tienes un modelo 'Sale' y una columna 'total'
        $dailyEarnings = Sale::whereDate('created_at', $today)
            ->where('status', 'paid') // Opcional: solo sumar si está pagado
            ->sum('total');

        // 2. Ganancias del Mes
        $monthlyEarnings = Sale::whereMonth('created_at', $currentMonth)
            ->whereYear('created_at', $currentYear)
            ->where('status', 'paid')
            ->sum('total');

        // 3. Ventas Totales del Mes (Cantidad de transacciones)
        $monthlySalesCount = Sale::whereMonth('created_at', $currentMonth)
            ->whereYear('created_at', $currentYear)
            ->count();

        // 4. Stock Bajo (Alertas)
        // Compara si el stock actual es menor o igual al stock mínimo definido
        $lowStockCount = Product::whereColumn('stock', '<=', 'min_stock')
            ->count();

        // 5. Últimas Ventas (Para la tabla)
        // Usamos 'with' para cargar la relación del cliente y evitar consultas N+1
        $recentSales = 0;

        return view('livewire.admin.dashboard', [
            'dailyEarnings' => $dailyEarnings,
            'monthlyEarnings' => $monthlyEarnings,
            'monthlySalesCount' => $monthlySalesCount,
            'lowStockCount' => $lowStockCount,
            'recentSales' => $recentSales,
        ]);
    }
}

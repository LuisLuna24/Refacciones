<?php

namespace App\Livewire\Admin;

use App\Models\Product;
use App\Models\Sale;
use Carbon\Carbon;
use Livewire\Component;

class Dashboard extends Component
{
    public function updatePrice()
    {
        // Definimos el IVA global que vas a aplicar
        $iva = 16;

        // Procesamos de 100 en 100 para no saturar la memoria RAM del servidor
        // y cargamos la relación 'category' para evitar lentitud (N+1 queries)
        Product::with('category')->chunk(100, function ($products) use ($iva) {
            foreach ($products as $product) {

                // Solo calculamos si tiene un costo mayor a 0 y una categoría asignada
                if ($product->cost > 0 && $product->category_id) {

                    // Obtenemos el porcentaje de la categoría
                    $porcent = $product->category ? $product->category->porcent : 0;

                    // 1. Calculamos el subtotal (Costo + Ganancia)
                    $subtotal = floatval($product->cost) + (floatval($product->cost) * floatval($porcent) / 100);

                    // 2. Aplicamos el IVA solo si el producto tiene el campo activado
                    if ($product->apply_iva) {
                        $total_final = $subtotal * (1 + ($iva / 100));
                    } else {
                        $total_final = $subtotal;
                    }

                    // 3. Formateamos y guardamos directo en la base de datos
                    $product->update([
                        'price' => number_format($total_final, 2, '.', '')
                    ]);
                }
            }
        });

        // Lanzamos una alerta de éxito al terminar
        $this->dispatch('swal', [
            'icon' => 'success',
            'title' => 'Éxito',
            'text' => 'Todos los precios han sido actualizados correctamente.'
        ]);
    }
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

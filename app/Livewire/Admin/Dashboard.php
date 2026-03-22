<?php

namespace App\Livewire\Admin;

use App\Models\Product;
use App\Models\Sale;
use Carbon\Carbon;
use Livewire\Component;

class Dashboard extends Component
{
    // Propiedades para el filtro de rango de fechas
    public $fromDate;
    public $toDate;

    /**
     * Se ejecuta al cargar el componente por primera vez.
     * Inicializa las fechas para que el rango por defecto sea el mes actual.
     */
    public function mount()
    {
        $this->fromDate = Carbon::now()->startOfMonth()->format('Y-m-d');
        $this->toDate = Carbon::now()->format('Y-m-d');
    }

    /**
     * Lógica para actualizar precios masivamente basándose en:
     * Costo + % de Categoría + IVA (si aplica).
     */
    public function updatePrice()
    {
        $iva = 16; // IVA configurable

        // Procesamos por bloques (chunks) para optimizar memoria RAM
        Product::with('category')->chunk(100, function ($products) use ($iva) {
            foreach ($products as $product) {
                if ($product->cost > 0 && $product->category_id) {

                    // Obtenemos porcentaje de la categoría (asumiendo columna 'porcent' en categories)
                    $porcent = $product->category->porcent ?? 0;

                    // 1. Subtotal = Costo + Ganancia
                    $subtotal = floatval($product->cost) + (floatval($product->cost) * floatval($porcent) / 100);

                    // 2. Aplicar IVA si el producto lo requiere
                    $total_final = $product->apply_iva
                        ? $subtotal * (1 + ($iva / 100))
                        : $subtotal;

                    // 3. Guardar con formato decimal correcto
                    $product->update([
                        'price' => number_format($total_final, 2, '.', '')
                    ]);
                }
            }
        });

        // Notificación de éxito para SweetAlert2 o similar
        $this->dispatch('swal', [
            'icon' => 'success',
            'title' => 'Precios Sincronizados',
            'text' => 'Se han recalculado los precios de venta según costos y utilidades.'
        ]);
    }

    /**
     * Renderiza la vista y calcula las estadísticas en tiempo real.
     */
    public function render()
    {
        $today = Carbon::today();
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        // 1. Ganancias del Día (Ventas con status '1' = completadas/pagadas)
        $dailyEarnings = Sale::whereDate('created_at', $today)
            ->where('status', '1')
            ->sum('total');

        // 2. Ganancias del Mes Actual
        $monthlyEarnings = Sale::whereMonth('created_at', $currentMonth)
            ->whereYear('created_at', $currentYear)
            ->where('status', '1')
            ->sum('total');

        // 3. VENTAS POR RANGO DE FECHAS (La nueva funcionalidad)
        // Usamos startOfDay y endOfDay para asegurar que tome todo el rango de tiempo
        $rangeEarnings = Sale::whereBetween('created_at', [
            Carbon::parse($this->fromDate)->startOfDay(),
            Carbon::parse($this->toDate)->endOfDay()
        ])
            ->where('status', '1')
            ->sum('total');

        // 4. Cantidad de ventas realizadas en el mes
        $monthlySalesCount = Sale::whereMonth('created_at', $currentMonth)
            ->whereYear('created_at', $currentYear)
            ->where('status', '1')
            ->count();

        // 5. Alerta de Stock Bajo
        // Compara stock actual contra el mínimo definido en el producto
        $lowStockCount = Product::whereColumn('stock', '<=', 'min_stock')
            ->count();

        // 6. Últimas 5 ventas para mostrar en tabla (si lo requieres)
        $recentSales = Sale::with('customer')
            ->latest()
            ->take(5)
            ->get();

        return view('livewire.admin.dashboard', [
            'dailyEarnings'     => $dailyEarnings,
            'monthlyEarnings'   => $monthlyEarnings,
            'rangeEarnings'     => $rangeEarnings,
            'monthlySalesCount' => $monthlySalesCount,
            'lowStockCount'     => $lowStockCount,
            'recentSales'       => $recentSales,
        ]);
    }
}

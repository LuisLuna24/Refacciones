<?php

namespace App\Livewire\Home\Lonas;

use Livewire\Component;

class Index extends Component
{
    public $width = 1.00;
    public $height = 1.00;
    public $quantity = 1;

    // El cobro mínimo ahora empata con el precio de 1 m²
    public $minPrice = 130.00;
    public $meterPrice = 130.00;
    public $designPrice = 100.00;

    // Opciones extras
    public $needsDesign = false;
    public $needsTubes = false;
    public $isUrgent = false;

    // NUEVO: Calculamos el precio por m² dependiendo del volumen total
    public function getCurrentPricePerSqmProperty()
    {
        $totalArea = $this->totalArea;

        if ($totalArea >= 4) {
            return 95.00; // Precio mayoreo a partir de 4 m²
        }

        return $this->meterPrice; // Precio normal de 1 a 3.99 m²
    }

    // NUEVO: Helper para saber el área total del pedido completo
    public function getTotalAreaProperty()
    {
        $w = (float) $this->width ?: 0.1;
        $h = (float) $this->height ?: 0.1;
        $q = (int) $this->quantity ?: 1;

        return ($w * $h) * $q;
    }

    public function getSubtotalProperty()
    {
        // Se multiplica el área total por el precio dinámico ($95 o $150)
        $costoImpresion = $this->totalArea * $this->currentPricePerSqm;
        
        // Verificamos si no alcanza el mínimo de $150
        $basePrice = max($costoImpresion, $this->minPrice);

        $w = (float) $this->width ?: 0.1;
        $q = (int) $this->quantity ?: 1;
        
        $extrasPerLona = $this->needsTubes ? ($w * 30) : 0;
        $totalExtras = $extrasPerLona * $q;

        $designCost = $this->needsDesign ? $this->designPrice : 0;

        return $basePrice + $totalExtras + $designCost;
    }

    public function getUrgentFeeProperty()
    {
        return $this->isUrgent ? ($this->subtotal * 0.20) : 0;
    }

    public function getTotalProperty()
    {
        return max(0, $this->subtotal + $this->urgent_fee);
    }

    public function getAreaProperty()
    {
        $w = (float) $this->width ?: 0;
        $h = (float) $this->height ?: 0;
        return $w * $h;
    }

    public function getIsMinimumChargeProperty()
    {
        return ($this->totalArea * $this->currentPricePerSqm) < $this->minPrice;
    }

    public function getWhatsappUrlProperty()
    {
        $w = (float) $this->width ?: 0.1;
        $h = (float) $this->height ?: 0.1;
        $q = (int) $this->quantity ?: 1;

        $design = $this->needsDesign ? 'Sí (+$150)' : 'No';
        $tubes = $this->needsTubes ? 'Sí' : 'No';
        $urgent = $this->isUrgent ? 'Sí (+20%)' : 'No';
        
        // Agregamos al mensaje a qué precio se lo estás dejando para que vea el descuento
        $precioAplicado = number_format($this->currentPricePerSqm, 2);
        $total = number_format($this->total, 2);

        $message = "¡Hola Two Brothers! Me gustaría hacer un pedido de lonas:\n\n";
        $message .= "📏 *Medidas:* {$w}m x {$h}m\n";
        $message .= "📦 *Cantidad:* {$q} pieza(s)\n";
        $message .= "🏷️ *Precio aplicado:* \${$precioAplicado} por m²\n";
        $message .= "🎨 *Diseño Gráfico:* {$design}\n";
        $message .= "🪵 *Tubos/Maderas:* {$tubes}\n";
        $message .= "⚡ *Servicio Urgente:* {$urgent}\n\n";
        $message .= "💰 *Total Estimado:* \${$total} MXN\n\n";
        $message .= "¿Me pueden confirmar el pedido?";

        $phone = "525632220120";

        return "https://wa.me/{$phone}?text=" . urlencode($message);
    }

    public function render()
    {
        return view('livewire.home.lonas.index');
    }
}
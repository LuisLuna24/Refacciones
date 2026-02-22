<?php

namespace App\Livewire\Home\Lonas;

use Livewire\Component;

class Index extends Component
{
    public $width = 1.00;
    public $height = 1.00;
    public $quantity = 1;

    // Precios
    public $pricePerSqm = 90.00;
    public $minPrice = 90.00;

    // Opciones extras
    public $needsDesign = false;
    public $needsTubes = false;
    public $isUrgent = false;

    public function getTotalProperty()
    {
        $w = (float) $this->width ?: 0.1;
        $h = (float) $this->height ?: 0.1;
        $q = (int) $this->quantity ?: 1;

        $area = $w * $h;
        $basePrice = max($area * $this->pricePerSqm, $this->minPrice);

        $extrasPerLona = $this->needsTubes ? ($w * 30) : 0;
        $designCost = $this->needsDesign ? 150.00 : 0;

        $subtotal = (($basePrice + $extrasPerLona) * $q) + $designCost;
        $urgentFee = $this->isUrgent ? ($subtotal * 0.20) : 0;

        return max(0, $subtotal + $urgentFee);
    }

    public function getUrgentFeeProperty()
    {
        $w = (float) $this->width ?: 0.1;
        $h = (float) $this->height ?: 0.1;
        $q = (int) $this->quantity ?: 1;

        $area = $w * $h;
        $basePrice = max($area * $this->pricePerSqm, $this->minPrice);
        $extrasPerLona = $this->needsTubes ? ($w * 30) : 0;
        $designCost = $this->needsDesign ? 150.00 : 0;

        $subtotal = (($basePrice + $extrasPerLona) * $q) + $designCost;

        return $this->isUrgent ? ($subtotal * 0.20) : 0;
    }

    public function getAreaProperty()
    {
        $w = (float) $this->width ?: 0;
        $h = (float) $this->height ?: 0;
        return $w * $h;
    }

    public function getIsMinimumChargeProperty()
    {
        $w = (float) $this->width ?: 0;
        $h = (float) $this->height ?: 0;
        return ($w * $h * $this->pricePerSqm) < $this->minPrice;
    }

    // NUEVO: Generador del enlace de WhatsApp
    public function getWhatsappUrlProperty()
    {
        $w = (float) $this->width ?: 0.1;
        $h = (float) $this->height ?: 0.1;
        $q = (int) $this->quantity ?: 1;

        $design = $this->needsDesign ? 'Sí (+$150)' : 'No';
        $tubes = $this->needsTubes ? 'Sí' : 'No';
        $urgent = $this->isUrgent ? 'Sí (+20%)' : 'No';
        $total = number_format($this->total, 2);

        $message = "¡Hola Two Brothers! Me gustaría hacer un pedido de lonas con los siguientes detalles:\n\n";
        $message .= "📏 *Medidas:* {$w}m x {$h}m\n";
        $message .= "📦 *Cantidad:* {$q} pieza(s)\n";
        $message .= "🎨 *Diseño Gráfico:* {$design}\n";
        $message .= "🪵 *Tubos/Maderas:* {$tubes}\n";
        $message .= "⚡ *Servicio Urgente:* {$urgent}\n\n";
        $message .= "💰 *Total Estimado:* \${$total} MXN\n\n";
        $message .= "¿Me pueden confirmar el pedido?";

        // Agregamos '52' al inicio por el código de país de México para la API de WhatsApp
        $phone = "525632220120";

        return "https://wa.me/{$phone}?text=" . urlencode($message);
    }

    public function render()
    {
        return view('livewire.home.lonas.index');
    }
}

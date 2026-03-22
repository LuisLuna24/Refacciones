<?php

namespace App\Enums;

enum PaymentMethod: int
{
    case Efectivo = 1;
    case Tarjeta = 2;
    case Transferencia = 3;
    case Paypal = 4;
}

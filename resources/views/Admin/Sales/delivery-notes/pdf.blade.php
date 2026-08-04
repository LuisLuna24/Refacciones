<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Delivery Note #{{ $model->serie }}-{{ str_pad($model->correlative, 4, '0', STR_PAD_LEFT) }}</title>
    <style>
        @page {
            margin: 0cm 0cm;
        }
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 12px;
            color: #333;
            margin-top: 3cm;
            margin-left: 2cm;
            margin-right: 2cm;
            margin-bottom: 2cm;
            line-height: 1.4;
        }

        .w-100 { width: 100%; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .text-bold { font-weight: bold; }
        .uppercase { text-transform: uppercase; }
        .gray-text { color: #555; }

        .header-table {
            width: 100%;
            margin-bottom: 18px;
            border: none;
        }
        .header-table td {
            vertical-align: top;
        }

        .info-box {
            background-color: #f8f9fa;
            border: 1px solid #e9ecef;
            padding: 10px;
            margin-bottom: 18px;
            border-radius: 4px;
        }
        .info-title {
            font-size: 10px;
            color: #777;
            text-transform: uppercase;
            margin-bottom: 5px;
            display: block;
        }

        .products-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 18px;
        }
        .products-table th {
            background-color: #2c3e50;
            color: #ffffff;
            padding: 8px;
            text-align: left;
            font-size: 11px;
            text-transform: uppercase;
        }
        .products-table td {
            border-bottom: 1px solid #ddd;
            padding: 8px;
            vertical-align: middle;
        }
        .tr-even {
            background-color: #f9f9f9;
        }

        .totals-table {
            width: 40%;
            float: right;
            border-collapse: collapse;
        }
        .totals-table td {
            padding: 5px;
            border-bottom: 1px solid #eee;
        }
        .total-row td {
            border-top: 2px solid #333;
            border-bottom: none;
            font-weight: bold;
            font-size: 14px;
            color: #000;
        }

        .company-block {
            border: 1px solid #d1d5db;
            padding: 10px;
            background: #f3f4f6;
            border-radius: 4px;
        }

        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            text-align: center;
            font-size: 9px;
            color: #777;
            border-top: 1px solid #eee;
            padding-top: 8px;
        }
    </style>
</head>
<body>
    <table class="header-table">
        <tr>
            <td style="width: 60%;">
                <div class="company-block">
                    <div style="font-size: 20px; font-weight: bold; color: #2c3e50;">Refacciones</div>
                    <div style="font-size: 14px;">Sistema de ventas e inventario</div>
                    <div class="gray-text" style="font-size: 10px; margin-top: 5px;">
                        Nota de entrega<br>
                        Control de pedidos y entregas
                    </div>
                </div>
            </td>

            <td style="width: 40%; text-align: right;">
                <div style="font-size: 18px; font-weight: bold; color: #e74c3c;">DELIVERY NOTE</div>
                <div style="font-size: 14px; margin-top: 5px;"># {{ $model->serie }}-{{ str_pad($model->correlative, 4, '0', STR_PAD_LEFT) }}</div>
                <div style="margin-top: 10px;">
                    <strong>Fecha:</strong> {{ $model->date ? $model->date->format('d/m/Y') : '-' }}<br>
                    <span style="font-size: 10px; color: #888;">Creado: {{ $model->created_at ? $model->created_at->format('H:i A') : '-' }}</span>
                </div>
            </td>
        </tr>
    </table>

    <br>

    <table class="w-100" style="margin-bottom: 20px;">
        <tr>
            <td style="width: 48%; padding-right: 2%;">
                <div class="info-box">
                    <span class="info-title">Cliente</span>
                    <strong style="font-size: 13px;">{{ optional($model->customer)->name ?? $model->guest_name ?? 'Público General' }}</strong><br>
                    <span style="font-size: 11px;">Email: {{ optional($model->customer)->email ?? $model->guest_email ?? '' }}</span><br>
                    <span style="font-size: 11px;">Teléfono: {{ optional($model->customer)->phone ?? $model->guest_phone ?? '' }}</span>
                </div>
            </td>
            <td style="width: 48%; padding-left: 2%;">
                <div class="info-box">
                    <span class="info-title">Detalles de entrega</span>
                    <strong>Almacén:</strong> {{ optional($model->warehouse)->name ?? 'Principal' }}<br>
                    <strong>Tipo:</strong> {{ $model->voucher_type ?? 'N/A' }}<br>
                    <strong>Estado:</strong> {{ $model->status == 1 ? 'Activo' : 'Cancelado' }}
                </div>
            </td>
        </tr>
    </table>

    <table class="products-table">
        <thead>
            <tr>
                <th style="width: 5%; text-align: center;">#</th>
                <th style="width: 45%;">Descripción del producto</th>
                <th style="width: 15%; text-align: right;">Precio Unit.</th>
                <th style="width: 15%; text-align: center;">Cant.</th>
                <th style="width: 20%; text-align: right;">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($model->items as $index => $item)
                <tr class="{{ $loop->even ? 'tr-even' : '' }}">
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>
                        <span class="text-bold">{{ optional($item->product)->name ?? $item->description ?? 'Producto' }}</span><br>
                        <span style="font-size: 9px; color: #666;">Cód: {{ optional($item->product)->sku ?? 'N/A' }}</span>
                    </td>
                    <td class="text-right">S/ {{ number_format((float) $item->price, 2) }}</td>
                    <td class="text-center">{{ $item->quantity }}</td>
                    <td class="text-right text-bold">S/ {{ number_format((float) $item->subtotal, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div style="width: 100%; clear: both;">
        <table class="totals-table">
            <tr>
                <td class="text-right gray-text">Total:</td>
                <td class="text-right">S/ {{ number_format((float) $model->total, 2) }}</td>
            </tr>
            <tr>
                <td class="text-right gray-text">Cuota:</td>
                <td class="text-right">S/ {{ number_format((float) $model->installment, 2) }}</td>
            </tr>
            <tr class="total-row">
                <td class="text-right">Saldo:</td>
                <td class="text-right">S/ {{ number_format((float) max(($model->total - $model->installment), 0), 2) }}</td>
            </tr>
        </table>
    </div>

    @if (!empty($model->observation))
        <div style="margin-top: 30px; clear: both; border-top: 1px solid #eee; padding-top: 10px;">
            <div class="info-title">Observaciones</div>
            <div>{{ $model->observation }}</div>
        </div>
    @endif

    <div class="footer">
        Documento generado electrónicamente por el sistema de Refacciones.<br>
        Gracias por su preferencia.
    </div>
</body>
</html>

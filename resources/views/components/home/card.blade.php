@props([
    'nombre' => 'Nombre del Producto',
    'descripcion' => 'Descripción corta del producto, sus características principales o beneficios.',
    'precio' => '0.00',
    'url' => null,
    'producto',
    'nuevo' => null,
])

@php
    $diasMaximoNuevo = 7;

    $fechaCreacion = $nuevo ? \Carbon\Carbon::parse($nuevo) : null;

    $fechaLimite = \Carbon\Carbon::now()->subDays($diasMaximoNuevo);

    $esNuevo = $fechaCreacion && $fechaCreacion->greaterThanOrEqualTo($fechaLimite);
@endphp

{{-- La clase 'dark:' en el contenedor principal es clave para invertir los colores --}}
<div
    class="w-full max-w-sm mx-auto bg-white dark:bg-gray-800 rounded-2xl shadow-xl overflow-hidden transform transition duration-500 hover:scale-[1.02] hover:shadow-2xl dark:shadow-indigo-500/20">

    {{-- Área de la Imagen con Etiqueta --}}
    <div class="relative h-48 flex justify-center items-center">
        <img class="w-full object-cover"
            src="{{ $producto['imagen_url'] ?? asset('img/logo.webp') }}"
            alt="{{ $producto['nombre'] ?? $nombre }}">

        {{-- Etiqueta de "Nuevo": mantiene su color para destacar --}}
        @if ($esNuevo)
            <div class="absolute top-3 left-3 bg-red-600 text-white text-xs font-bold px-3 py-1 rounded-full shadow-md">
                🔥 NUEVO
            </div>
        @endif
    </div>

    {{-- Contenido de la Tarjeta --}}
    <div class="p-6">

        {{-- Título y Descripción: ajustados para el modo oscuro --}}
        <h3 class="text-2xl font-extrabold text-gray-800 dark:text-white mb-2 leading-tight">
            {{ $nombre }}
        </h3>
        <!--p class="text-gray-500 dark:text-gray-400 text-sm mb-4 line-clamp-2">
            {{ $descripcion }}
        </p-->

        <div class="flex items-baseline justify-between mb-5">
            {{-- Precio Destacado: color consistente para énfasis --}}
            <span class="text-3xl font-black text-indigo-700 dark:text-indigo-400">
                ${{ number_format($precio, 2) }}
            </span>
        </div>

        {{-- Botón de Acción
        @if ($url)
            <a href="{{ $url }}" target="_blank"
                class="block w-full text-center bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-4 rounded-xl transition duration-200 shadow-md hover:shadow-lg focus:outline-none focus:ring-4 focus:ring-green-500 focus:ring-opacity-50">
                🛒 COMPRAR AHORA
            </a>
        @else
            @php
                $whatsapp_numero = '5632220120';
                $mensaje = urlencode('Hola, me interesa cotizar el producto: ' . $nombre);
                $whatsapp_url = 'https://wa.me/' . $whatsapp_numero . '?text=' . $mensaje;
            @endphp
            <a href="{{ $whatsapp_url }}" target="_blank"
                class="block w-full text-center bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-xl transition duration-200 shadow-md hover:shadow-lg focus:outline-none focus:ring-4 focus:ring-blue-500 focus:ring-opacity-50">
                💬 COTIZAR por WhatsApp
            </a>
        @endif --}}

        <a href="{{ $url }}" target="_blank"
            class="block w-full text-center bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-4 rounded-xl transition duration-200 shadow-md hover:shadow-lg focus:outline-none focus:ring-4 focus:ring-green-500 focus:ring-opacity-50">
            Ver Producto
        </a>
    </div>
</div>

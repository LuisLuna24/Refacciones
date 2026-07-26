@if (!$vinilType->prices->isEmpty())
    <x-w-button wire:click="showPrices({{ $vinilType->id }})">
        Configurar Precios
    </x-w-button>
@else
    <span class="text-emerald-600 font-semibold">
        ${{ number_format($vinilType->price_default, 2) }} por {{ $vinilType->unity }}
    </span>
@endif

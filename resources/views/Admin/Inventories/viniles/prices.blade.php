<x-w-button :color="$vinilType->prices->isEmpty() ? 'secondary' : 'primary'" wire:click="showPrices({{ $vinilType->id }})">
    Precios
</x-w-button>

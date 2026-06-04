<x-w-button :color="$product->tags->isEmpty() ? 'secondary' : 'primary'" wire:click="showTags({{ $product->id }})">
    Tags
</x-w-button>

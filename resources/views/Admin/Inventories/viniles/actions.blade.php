<div class="flex items-center space-x-2">
    @can('edit-viniles')
        <x-w-button href="{{ route('admin.viniles.edit', $vinilType) }}" blue xs>{!! file_get_contents(public_path('svg/edit.svg')) !!}</x-w-button>
    @endcan
    @can('delete-viniles')
        <form action="{{ route('admin.viniles.destroy', $vinilType) }}" class="delete-form" method="post">

            @csrf
            @method('DELETE')

            <x-w-button type="submit" red xs>{!! file_get_contents(public_path('svg/trash.svg')) !!}</x-w-button>
        </form>
    @endcan
</div>

<div>
    <x-slot name="header">
        <div class="flex items-center justify-between space-x-0.5">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Photos') }}
            </h2>
            <div>
                <x-button positive label="Nuevo" icon="plus" wire:navigate href="{{ route('admin.photo.create') }}"/>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                        @forelse ($photos as $key => $photo)
                            {{ $photo->id }}
                        @empty
                            <span class="w-full text-center my-10 py-4">SIN REGISTROS</span>
                        @endforelse

                        @if ($photos->hasMorePages() || !$photos->onFirstPage())
                            <div class="my-5 py-5 mx-auto">
                                {{ $photos->links() }}
                            </div>
                        @endif
                </div>
            </div>
        </div>
    </div>
</div>

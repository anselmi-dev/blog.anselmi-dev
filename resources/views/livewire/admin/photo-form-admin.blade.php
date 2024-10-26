<div>
    <x-slot name="header">
        <div class="flex items-center justify-between space-x-0.5">
            @if ($photo->exists)
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    {{ $photo->title }}
                </h2>
                <div>
                    <x-button negative label="{{ __('Delete') }}" icon="trash" wire:navigate href="{{ route('admin.photo.create') }}"/>
                </div>
            @else
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    {{ __('New photo') }}
                </h2>
            @endif
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <form wire:submit="submit" class="p-6 space-y-6">
                    <x-input label="{{ __('Title') }}" placeholder="{{ __('Photo title') }}" wire:model="photo.title"/>

                    <div class="grid md:grid-cols-2 space-y-6 md:space-y-0 md:space-x-2">
                        <x-select
                            label="Select Tags"
                            placeholder="Select many Tags"
                            multiselect
                            :options="$this->list_tags"
                            option-label="name"
                            option-value="id"
                            wire:model="tags"
                        />
                    </div>

                    <div wire:ignore>
                        <div id="photo-content"></div>
                    </div>
                    

                    @if ($photo->cover)
                        <div class="bg-gray-100 p-2 text-center">
                            <a href="{{ $photo->cover->getFullUrl() }}" target="__blank">
                                <img src="{{ $photo->cover->getUrl('thumb') }}" alt="">
                            </a>
                        </div>
                    @endif

                    <input type="file" wire:model="cover">

                    <x-errors />

                    <div class="flex items-center space-x-1 mt-2 pt-4 border-t">

                        <x-button dark label="{{ __('Cancel') }}" wire:navigate href="{{ route('admin.photos') }}"/>

                        <x-button primary label="{{ __('Guardar') }}" icon="trash" type="submit"/>

                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('styles')
	<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
    <style>
        .note-editable {
            background: #FFF;
        }
    </style>
@endpush

@push('scripts')
    <script type="text/javascript" src="{{ asset('dist/jquery-v3.2.1.js') }}"></script>
    <script src="{{ asset('dist/summernote-lite-v0.8.18.min.js') }}"></script>
    <script>
        function iniEditor(id, variable) {
            var summernote = $(id).summernote({
                placeholder: 'Contenido',
                tabsize: 2,
                height: 500,
                dialogsInBody: true,
                codemirror: { // codemirror options
                    theme: 'monokai'
                },
                // toolbar: [
                    // ['view', ['fullscreen', 'codeview', 'help']],
                    // ['insert', ['link', 'picture', 'video']],
                // ],
                callbacks: {
                    onChange: function(contents, $editable) {
                        @this.set(variable, contents, false);
                    }
                }
            });

            $(id).summernote('code', @this.get(variable))

            Livewire.on('clear-editor', () => {
                $(id).summernote('code', '');
            });
        }
    </script>

    <script>
        document.addEventListener('livewire:initialized', () => {
            iniEditor("#photo-content", 'photo.description');
        });
    </script>
@endpush

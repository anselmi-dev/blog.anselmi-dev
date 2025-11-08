@props([
    'items',
    'activeTab'
])

<div x-data="{
        activeTab: '{{ $activeTab }}',
        maxHeight: 0,
        setMaxHeight() {
            this.maxHeight = Math.max(
                ...Array.from(document.querySelectorAll('.tab-content')).map(el => el.scrollHeight)
            );
        }
    }"
    x-init="setMaxHeight"
    @resize.window="setMaxHeight"
    class="flex"
    >
    <!-- Tab buttons -->
    <div class="flex flex-col w-1/4 lg:w-1/5 _border-r _dark:border-opacity-20">
        {{ $buttons }}
    </div>

    <!-- Tab content -->
    {{-- <div class="w-3/4 px-4" :style="{ height: maxHeight + 'px' }"> --}}
    <div class="w-3/4 lg:w-3/5 pl-4 h-full relative overflow-hidden">
        {{ $contents }}
    </div>
</div>
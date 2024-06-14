<div class="flex items-start space-x-2 w-full">
    <div class="min-w-[0.75rem] min-h-3 w-3 h-3 rounded-full bg-slate-600 mt-[8px] z-1"></div>
    <div class="flex flex-col">
        @isset ($date)
            <div>
                <span class="rounded-xl border border-gray-500 text-xs opacity-60 px-2">{{ $date }}</span>
            </div>
        @endisset
        @isset($title)
            <span class="text-base">{{ $title }}</span>
        @endisset
        <div class="text-xs opacity-60">
            {{ $slot }}
        </div>
    </div>
</div>

@props([
    'field',
    'placement' => 'input',
])

@error($field)
    <span
        class="group/tooltip pointer-events-auto absolute z-10 inline-flex outline-none {{ $placement === 'textarea' ? 'right-0 top-2' : 'right-0 top-1/2 -translate-y-1/2' }}"
        tabindex="0"
        role="img"
        aria-label="{{ $message }}"
    >
        <span
            class="inline-flex size-7 cursor-default items-center justify-center rounded-full bg-red-600/10 text-red-600 transition group-hover/tooltip:bg-red-600/15 group-focus-visible/tooltip:bg-red-600/15"
        >
            <svg class="size-3.5 shrink-0" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                <path
                    fill-rule="evenodd"
                    d="M8.485 2.495c.673-1.167 2.357-1.167 3.03 0l6.28 10.875c.673 1.167-.17 2.625-1.516 2.625H3.72c-1.347 0-2.189-1.458-1.515-2.625L8.485 2.495zM10 5a.75.75 0 01.75.75v3.5a.75.75 0 01-1.5 0v-3.5A.75.75 0 0110 5zm0 9a1 1 0 100-2 1 1 0 000 2z"
                    clip-rule="evenodd"
                />
            </svg>
        </span>
        <span
            role="tooltip"
            class="pointer-events-none invisible absolute right-full top-1/2 z-50 mr-2 w-max max-w-[min(15rem,calc(100vw-3rem))] -translate-y-1/2 rounded-lg bg-zinc-900 px-2.5 py-2 text-left text-xs font-medium leading-snug text-white opacity-0 shadow-lg ring-1 ring-white/10 transition duration-150 ease-out group-hover/tooltip:visible group-hover/tooltip:opacity-100 group-focus-visible/tooltip:visible group-focus-visible/tooltip:opacity-100"
        >
            <span
                class="absolute left-full top-1/2 -mt-px h-0 w-0 -translate-y-1/2 border-y-[6px] border-l-[6px] border-y-transparent border-l-zinc-900"
                aria-hidden="true"
            ></span>
            {{ $message }}
        </span>
    </span>
@enderror

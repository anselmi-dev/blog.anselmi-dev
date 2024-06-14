<div
    class="[&:has(~.active)]:opacity-25 [&.active~*]:opacity-25 transition-opacity duration-200 relative inline-flex justify-center  z-30"
    x-data="{ open: false }"
    :class="{ 'active': open }"
    @mouseover.outside="open = false"
    @focusout="await $nextTick();!$el.contains($focus.focused()) && (open = false)"
>
    <span type="button" :class="{ 'rotate-0': open }" @mouseover="open = true" @focus="open = true">
        {{ $slot }}
    </span>

    <div
        id="testimonial-03"
        role="tooltip"
        class="absolute top-full pt-1 [&[x-cloak]]:hidden"
        x-ref="tooltip"
        x-cloak
    >
        <div
            class="relative w-80 after:absolute after:-top-1.5 after:left-1/2 after:-translate-x-1/2 after:h-3 after:w-3 after:rounded-tl-sm after:rotate-45 after:bg-slate-900"
            x-show="open"
            x-transition:enter="transition ease-[cubic-bezier(.5,.85,.25,1.8)] duration-200 delay-100"
            x-transition:enter-start="opacity-0 translate-y-2"
            x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-[cubic-bezier(.5,.85,.25,1.8)] duration-100 delay-100"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
        >
            <div
                class="relative bg-slate-900 p-5 rounded-3xl shadow-xl text-left text-sm text-slate-200 font-medium space-y-3"
                x-init="$watch('open', value => { $nextTick(() => {
                    $refs.tooltip.getBoundingClientRect().left < 0 ? $el.style.left = Math.abs($refs.tooltip.getBoundingClientRect().left) + $root.getBoundingClientRect().left - 4 + 'px' : $el.style.left = null;
                    $refs.tooltip.getBoundingClientRect().right > document.documentElement.offsetWidth ? $el.style.right = Math.abs($refs.tooltip.getBoundingClientRect().right) - $root.getBoundingClientRect().right - 4 + 'px' : $el.style.right = null;
                } )} )"
            >
                {{ $message }}
            </div>
        </div>
    </div>
</div>

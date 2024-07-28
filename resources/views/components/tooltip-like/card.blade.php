@php
    $view = 'components.tooltip-like.items.' . $item;
@endphp

<div
    class="[&:has(~.active)]:opacity-25 [&.active~*]:opacity-25 transition-opacity duration-200 relative inline-flex justify-center"
    x-data="{ open: false }"
    :class="{ 'active z-1': open }"
    @mouseover.outside="open = false"
    @focusout="await $nextTick();!$el.contains($focus.focused()) && (open = false)"
>
    <span type="button" class="cursor-default font-bold underline dark:text-app-default" :class="{ 'rotate-0': open }" @mouseover="open = true" @focus="open = true">
        <span class="relative pr-1">
            <span>
                {{ $label }}
            </span>
            <x-hugeicons-help-square class="absolute top-0 -right-3 h-2.5"/>
        </span>
    </span>

    <div
        id="testimonial-03"
        role="tooltip"
        class="absolute top-full pt-1 [&[x-cloak]]:hidden z-1"
        x-ref="tooltip"
        x-cloak
    >
        <div
            class="relative w-80 after:absolute after:-top-1.5 after:left-1/2 after:-translate-x-1/2 after:h-3 after:w-3 after:rounded-tl-sm after:rotate-45 after:bg-secondary-dark"
            x-show="open"
            x-transition:enter="transition ease-[cubic-bezier(.5,.85,.25,1.8)] duration-200 delay-100"
            x-transition:enter-start="opacity-0 translate-y-2"
            x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-[cubic-bezier(.5,.85,.25,1.8)] duration-100 delay-100"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
        >
            <div
                class="relative bg-secondary-dark p-5 rounded-lg shadow-xl text-left text-sm text-slate-200 font-medium space-y-3"
                x-init="$watch('open', value => { $nextTick(() => {
                    $refs.tooltip.getBoundingClientRect().left < 0 ? $el.style.left = Math.abs($refs.tooltip.getBoundingClientRect().left) + $root.getBoundingClientRect().left - 4 + 'px' : $el.style.left = null;
                    $refs.tooltip.getBoundingClientRect().right > document.documentElement.offsetWidth ? $el.style.right = Math.abs($refs.tooltip.getBoundingClientRect().right) - $root.getBoundingClientRect().right - 4 + 'px' : $el.style.right = null;
                } )} )"
            >
                <svg class="fill-app-default" xmlns="http://www.w3.org/2000/svg" width="17" height="14" aria-hidden="true">
                    <path fill-rule="nonzero" d="M2.014 3.68c.276-1.267.82-2.198 1.629-2.79C4.453.295 5.627 0 7.167 0c.514 0 .908.02 1.185.061L5.035 10.49c-.75 2.494-2.429 3.66-5.035 3.496L2.014 3.68Zm8.648 0c.237-1.227.77-2.147 1.6-2.76C13.09.307 14.274 0 15.814 0c.514 0 .909.02 1.185.061L13.683 10.49c-.79 2.494-2.468 3.66-5.035 3.496L10.662 3.68Z" />
                </svg>

                @includeIf($view)
            </div>
        </div>
    </div>
</div>

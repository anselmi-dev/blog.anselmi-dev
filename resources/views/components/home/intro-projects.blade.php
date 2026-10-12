@props([
    'projects' => [],
])

<section
    id="proyectos"
    aria-labelledby="intro-proyectos"
    class="border-t border-zinc-200"
    data-reveal="fade-up"
    data-reveal-scroll
    data-reveal-stagger="0.08"
>
    <div class="mx-auto max-w-8xl py-16">
        <div data-reveal-item>
            <span class="mb-6 block text-xs font-medium tabular-nums tracking-widest text-zinc-400">04</span>
            <h2 id="intro-proyectos" class="text-lg font-bold text-black sm:text-xl">
                {{ __('Proyectos') }}
            </h2>
        </div>

        <ul class="mt-8 border-t-2 border-zinc-900" data-reveal-item>
            @foreach ($projects as $slug => $project)
                <li class="border-b border-zinc-200">
                    <a
                        href="{{ localized_route('projects.show', $slug) }}"
                        wire:navigate
                        class="group flex items-start justify-between gap-6 py-6 transition hover:bg-zinc-50/80 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-zinc-900 sm:gap-10 sm:py-7"
                    >
                        <div class="min-w-0 flex-1">
                            <div class="flex items-baseline gap-3">
                                <span class="text-xs font-medium tabular-nums tracking-widest text-zinc-400">
                                    {{ $project['index'] }}
                                </span>
                                <h3 class="text-base font-bold text-zinc-900 sm:text-lg">
                                    {{ $project['title'] }}
                                </h3>
                            </div>

                            <p class="mt-2 max-w-3xl text-sm leading-relaxed text-zinc-600 sm:text-[0.9375rem]">
                                {{ $project['excerpt'] }}
                            </p>

                            @if (! empty($project['tags']))
                                <p class="mt-3 text-xs leading-relaxed text-zinc-400 sm:text-sm">
                                    {{ collect($project['tags'])->take(8)->implode(' · ') }}
                                </p>
                            @endif
                        </div>

                        <x-icon
                            name="arrow-right"
                            class="mt-1 size-4 shrink-0 text-zinc-900 transition-transform duration-200 group-hover:translate-x-0.5"
                        />
                    </a>
                </li>
            @endforeach
        </ul>

        <div data-reveal-item>
            <a
                href="{{ localized_route('projects') }}"
                wire:navigate
                class="group inline-flex items-center justify-between gap-4 pt-6 font-semibold text-black transition hover:text-zinc-700 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-zinc-900"
            >
                <span>{{ __('Ver portafolio completo') }}</span>
                <x-icon name="arrow-right" class="size-4 shrink-0 text-zinc-900 transition-transform duration-200 group-hover:translate-x-0.5" />
            </a>
        </div>
    </div>
</section>

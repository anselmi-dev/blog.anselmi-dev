@php
    $weeks = $heatmap['weeks'];
    $months = $heatmap['months'];
    $dayLabels = $heatmap['days'];
    $flatDays = collect($weeks)->flatMap(fn ($week) => $week['days'])->keyBy('date');
@endphp

<div
    class="w-full"
    x-data="{
        selected: '{{ $flatDays->keys()->first(fn ($d) => count($flatDays[$d]['notes']) > 0) ?? $flatDays->keys()->first() }}',
        page: 0,
        get day() {
            return this.days[this.selected] ?? null;
        },
        days: {{ Js::from($flatDays) }},
    }"
>
    <article class="rounded-[28px] border border-white/6 bg-[#101012] p-6 shadow-[0_24px_80px_rgba(0,0,0,0.45)] sm:p-8">
        <header class="mb-8 flex items-start justify-between gap-4">
            <div>
                <h1 class="text-[2rem] font-semibold tracking-tight text-white sm:text-[2.15rem]">
                    Me Calendar
                </h1>
                <p class="mt-1 text-sm text-[#6b6b73]">
                    Notes &amp; events
                </p>
            </div>

            <button
                type="button"
                class="inline-flex size-9 items-center justify-center rounded-full text-[#6b6b73] transition hover:bg-white/5 hover:text-white"
                aria-label="Opciones"
            >
                <svg class="size-5" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                    <circle cx="12" cy="5" r="1.6" />
                    <circle cx="12" cy="12" r="1.6" />
                    <circle cx="12" cy="19" r="1.6" />
                </svg>
            </button>
        </header>

        {{-- Month labels --}}
        <div class="mb-2 grid gap-1.5" style="grid-template-columns: 2.25rem repeat({{ count($weeks) }}, minmax(0, 1fr));">
            <div></div>
            @foreach ($months as $month)
                <div
                    class="text-center text-[0.7rem] font-medium uppercase tracking-[0.08em] text-[#6b6b73]"
                    style="grid-column: span {{ $month['span'] }} / span {{ $month['span'] }}"
                >
                    {{ $month['label'] }}
                </div>
            @endforeach
        </div>

        {{-- Heat map grid: rows = weekdays, cols = weeks --}}
        <div class="grid gap-1.5" style="grid-template-columns: 2.25rem repeat({{ count($weeks) }}, minmax(0, 1fr));">
            @foreach ($dayLabels as $rowIndex => $dayLabel)
                <div class="flex items-center text-[0.7rem] font-medium text-[#6b6b73]">
                    {{ $dayLabel }}
                </div>

                @foreach ($weeks as $week)
                    @php($cell = $week['days'][$rowIndex])
                    <button
                        type="button"
                        @click="selected = '{{ $cell['date'] }}'"
                        :class="selected === '{{ $cell['date'] }}' ? 'ring-2 ring-white/35 scale-[1.04]' : 'ring-0'"
                        class="cal-cell cal-cell--{{ $cell['level'] }} aspect-square w-full rounded-[10px] text-[0.65rem] font-semibold tabular-nums transition duration-200 hover:brightness-110 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#d6f04a] sm:rounded-xl sm:text-xs"
                        aria-label="{{ $cell['label'] }}"
                    >
                        {{ $cell['day'] }}
                    </button>
                @endforeach
            @endforeach
        </div>

        {{-- Pagination dots (visual only for now) --}}
        <div class="mt-8 flex items-center justify-center gap-2" aria-hidden="true">
            <span class="h-1.5 w-6 rounded-full bg-[#d6f04a]"></span>
            <span class="size-1.5 rounded-full bg-white/20"></span>
            <span class="size-1.5 rounded-full bg-white/20"></span>
        </div>
    </article>

    {{-- Day detail / notes --}}
    <section class="mt-5 rounded-[24px] border border-white/6 bg-[#101012] p-5 sm:p-6">
        <template x-if="day">
            <div>
                <div class="flex items-center justify-between gap-3">
                    <div>
                        <p class="text-[0.7rem] font-medium uppercase tracking-[0.12em] text-[#6b6b73]">Selected day</p>
                        <h2 class="mt-1 text-lg font-semibold text-white" x-text="day.label"></h2>
                    </div>
                    <span
                        class="inline-flex size-8 items-center justify-center rounded-full text-xs font-bold"
                        :class="{
                            'bg-[#1c1c1f] text-zinc-400': day.level === 0,
                            'bg-[#3f4a2c] text-white': day.level === 1,
                            'bg-[#6a7a3a] text-white': day.level === 2,
                            'bg-[#d6f04a] text-zinc-900': day.level === 3,
                        }"
                        x-text="day.level"
                    ></span>
                </div>

                <ul class="mt-4 space-y-2" x-show="day.notes.length">
                    <template x-for="(note, i) in day.notes" :key="i">
                        <li class="rounded-2xl border border-white/6 bg-white/[0.03] px-4 py-3 text-sm text-zinc-200" x-text="note"></li>
                    </template>
                </ul>

                <p
                    class="mt-4 text-sm text-[#6b6b73]"
                    x-show="! day.notes.length"
                >
                    Sin notas todavía. Acá irán eventos, recordatorios y apuntes del día.
                </p>

                <button
                    type="button"
                    class="mt-5 inline-flex w-full items-center justify-center gap-2 rounded-2xl bg-[#d6f04a] px-4 py-3 text-sm font-semibold text-zinc-900 transition hover:brightness-105"
                >
                    <span class="text-lg leading-none">+</span>
                    Add note
                </button>
            </div>
        </template>
    </section>
</div>

<style>
    .cal-cell {
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .cal-cell--0 {
        background: #1c1c1f;
        color: rgba(255, 255, 255, 0.55);
    }

    .cal-cell--1 {
        background: #3f4a2c;
        color: #f4f4f5;
    }

    .cal-cell--2 {
        background: #6a7a3a;
        color: #fafafa;
    }

    .cal-cell--3 {
        background: #d6f04a;
        color: #111;
    }
</style>

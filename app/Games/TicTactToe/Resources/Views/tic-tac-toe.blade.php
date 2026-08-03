@php
    $coords = $this->lineSvgCoords();
@endphp

<div class="mx-auto max-w-lg">
    <div class="mb-8 text-center">
        <h3 class="text-2xl font-bold tracking-tight text-zinc-900 sm:text-3xl">
            Piensa más claro. <span class="text-zinc-600">Decide mejor.</span>
        </h3>
        <p class="mt-3 text-sm leading-relaxed text-zinc-500 sm:text-base">
            Tres en raya contra una IA que juega al minimax: no te regala nada.
        </p>
    </div>

    <div
        class="tictactoe-board relative mx-auto aspect-square w-full max-w-[min(100%,20rem)] bg-white text-zinc-900/90 transition-opacity duration-300"
        @class(['opacity-90' => $waitingForAi])
    >
        {{-- Rejilla “flotante”: líneas con extremos redondeados, sin tocar el borde exterior --}}
        <svg
            class="pointer-events-none absolute inset-0 size-full"
            viewBox="0 0 100 100"
            fill="none"
            aria-hidden="true"
        >
            @php
                $m = 8;
                $g1 = 100 / 3;
                $g2 = 200 / 3;
            @endphp
            <line
                x1="{{ $g1 }}"
                y1="{{ $m }}"
                x2="{{ $g1 }}"
                y2="{{ 100 - $m }}"
                stroke="currentColor"
                stroke-linecap="round"
                style="stroke-width: var(--tt-stroke)"
            />
            <line
                x1="{{ $g2 }}"
                y1="{{ $m }}"
                x2="{{ $g2 }}"
                y2="{{ 100 - $m }}"
                stroke="currentColor"
                stroke-linecap="round"
                style="stroke-width: var(--tt-stroke)"
            />
            <line
                x1="{{ $m }}"
                y1="{{ $g1 }}"
                x2="{{ 100 - $m }}"
                y2="{{ $g1 }}"
                stroke="currentColor"
                stroke-linecap="round"
                style="stroke-width: var(--tt-stroke)"
            />
            <line
                x1="{{ $m }}"
                y1="{{ $g2 }}"
                x2="{{ 100 - $m }}"
                y2="{{ $g2 }}"
                stroke="currentColor"
                stroke-linecap="round"
                style="stroke-width: var(--tt-stroke)"
            />
        </svg>

        <div
            class="relative z-[1] grid h-full w-full grid-cols-3 grid-rows-3"
            role="grid"
            aria-label="Tablero tres en raya"
            aria-busy="{{ $waitingForAi ? 'true' : 'false' }}"
        >
            @foreach (range(0, 8) as $i)
                <button
                    type="button"
                    wire:click="play({{ $i }})"
                    wire:key="cell-{{ $i }}-{{ $board[$i] ?? 'e' }}"
                    @class([
                        'relative flex items-center justify-center outline-none transition-colors',
                        'cursor-default opacity-60' => $status !== 'playing' || $board[$i] !== null || $waitingForAi,
                        'cursor-pointer hover:bg-zinc-900/[0.03] focus-visible:bg-zinc-900/[0.05] focus-visible:ring-2 focus-visible:ring-zinc-900/30 focus-visible:ring-offset-0' => $status === 'playing' && $board[$i] === null && ! $waitingForAi,
                    ])
                    @disabled($status !== 'playing' || $board[$i] !== null || $waitingForAi)
                    aria-label="Casilla {{ $i + 1 }}"
                >
                    @if ($board[$i] === 'X')
                        <span class="tictactoe-mark inline-flex size-[min(68%,4.25rem)] items-center justify-center" aria-hidden="true">
                            <svg class="size-full" viewBox="0 0 100 100" fill="none">
                                <line
                                    x1="24"
                                    y1="24"
                                    x2="76"
                                    y2="76"
                                    stroke="currentColor"
                                    stroke-linecap="round"
                                    style="stroke-width: var(--tt-stroke)"
                                />
                                <line
                                    x1="76"
                                    y1="24"
                                    x2="24"
                                    y2="76"
                                    stroke="currentColor"
                                    stroke-linecap="round"
                                    style="stroke-width: var(--tt-stroke)"
                                />
                            </svg>
                        </span>
                    @elseif ($board[$i] === 'O')
                        <span class="tictactoe-mark inline-flex size-[min(68%,4.25rem)] items-center justify-center" aria-hidden="true">
                            <svg class="size-full" viewBox="0 0 100 100" fill="none">
                                <circle
                                    cx="50"
                                    cy="50"
                                    r="30"
                                    stroke="currentColor"
                                    style="stroke-width: var(--tt-stroke)"
                                />
                            </svg>
                        </span>
                    @endif
                </button>
            @endforeach
        </div>

        @if ($coords)
            <svg
                class="pointer-events-none absolute inset-0 z-[2] size-full"
                viewBox="0 0 100 100"
                preserveAspectRatio="none"
                aria-hidden="true"
            >
                <line
                    x1="{{ $coords[0] }}"
                    y1="{{ $coords[1] }}"
                    x2="{{ $coords[2] }}"
                    y2="{{ $coords[3] }}"
                    stroke="#dc2626"
                    stroke-width="3.2"
                    stroke-linecap="round"
                />
            </svg>
        @endif
    </div>

    <div class="mt-6 flex flex-col items-center gap-3 text-center">
        @if ($status === 'won_x')
            <p class="text-sm font-medium text-zinc-900">Ganaste (esta vez la IA falló el empate… o no).</p>
        @elseif ($status === 'won_o')
            <p class="text-sm font-medium text-zinc-900">Gana la máquina. ¿Revancha?</p>
        @elseif ($status === 'draw')
            <p class="text-sm font-medium text-zinc-600">Empate — ambos jugaron bien.</p>
        @else
            <p class="text-sm text-zinc-500">Tú eres <span class="font-semibold text-zinc-800">X</span>, la IA es <span class="font-semibold text-zinc-800">O</span>. Empezás vos.</p>
        @endif

        <button
            type="button"
            wire:click="resetBoard"
            class="rounded-full border border-zinc-300 px-4 py-2 text-sm font-medium text-zinc-700 transition hover:border-zinc-400 hover:bg-zinc-50 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-zinc-900"
        >
            Nueva partida
        </button>
    </div>
</div>

{{-- El panel no tiene un tema propio de Tailwind: la terminal va con estilos en línea. --}}
<x-filament-panels::page>
    {{ $this->form }}

    @if ($runs !== [])
        <div style="display: flex; flex-direction: column; gap: 1rem;">
            <div style="display: flex; align-items: center; justify-content: space-between;">
                <h2 style="font-size: 1rem; font-weight: 600;">Salida</h2>
                <x-filament::link tag="button" wire:click="clearRuns" color="gray" size="sm" icon="heroicon-o-trash">
                    Limpiar
                </x-filament::link>
            </div>

            @foreach ($runs as $index => $run)
                <div
                    wire:key="run-{{ count($runs) - $index }}"
                    style="overflow: hidden; border-radius: 0.75rem; background: #0b0f14; box-shadow: 0 0 0 1px rgb(0 0 0 / 0.1); {{ $index > 0 ? 'opacity: 0.75;' : '' }}"
                >
                    <div style="display: flex; flex-wrap: wrap; align-items: center; gap: 0.5rem 0.75rem; padding: 0.625rem 1rem; border-bottom: 1px solid rgb(255 255 255 / 0.1); font-family: ui-monospace, SFMono-Regular, Menlo, monospace; font-size: 0.75rem;">
                        <span style="color: #6b7280;">$</span>
                        <span style="flex: 1; min-width: 0; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; color: #f3f4f6;">php artisan {{ $run['command'] }}</span>
                        <x-filament::badge :color="$run['exit_code'] === 0 ? 'success' : 'danger'" size="sm">
                            {{ $run['exit_code'] === 0 ? 'OK' : 'Error '.$run['exit_code'] }}
                        </x-filament::badge>
                        <span style="color: #6b7280;">{{ $run['duration_ms'] }} ms · {{ $run['ran_at'] }}</span>
                    </div>
                    <pre style="margin: 0; max-height: 28rem; overflow: auto; padding: 0.75rem 1rem; font-family: ui-monospace, SFMono-Regular, Menlo, monospace; font-size: 0.75rem; line-height: 1.6; white-space: pre; color: #e5e7eb;">{{ $run['output'] }}</pre>
                </div>
            @endforeach
        </div>
    @endif
</x-filament-panels::page>

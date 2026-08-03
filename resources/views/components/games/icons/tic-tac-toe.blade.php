@props(['class' => 'size-12'])

<svg {{ $attributes->merge(['class' => $class]) }} viewBox="0 0 48 48" fill="none" aria-hidden="true">
    {{-- Grid lines --}}
    <line x1="16" y1="6"  x2="16" y2="42" stroke="#a1a1aa" stroke-width="2.5" stroke-linecap="round"/>
    <line x1="32" y1="6"  x2="32" y2="42" stroke="#a1a1aa" stroke-width="2.5" stroke-linecap="round"/>
    <line x1="6"  y1="16" x2="42" y2="16" stroke="#a1a1aa" stroke-width="2.5" stroke-linecap="round"/>
    <line x1="6"  y1="32" x2="42" y2="32" stroke="#a1a1aa" stroke-width="2.5" stroke-linecap="round"/>

    {{-- X (top-left cell) --}}
    <line x1="9"  y1="9"  x2="13" y2="13" stroke="#ef4444" stroke-width="2.5" stroke-linecap="round"/>
    <line x1="13" y1="9"  x2="9"  y2="13" stroke="#ef4444" stroke-width="2.5" stroke-linecap="round"/>

    {{-- O (center cell) --}}
    <circle cx="24" cy="24" r="4.5" stroke="#3b82f6" stroke-width="2.5"/>

    {{-- X (bottom-right cell) --}}
    <line x1="35" y1="35" x2="39" y2="39" stroke="#ef4444" stroke-width="2.5" stroke-linecap="round"/>
    <line x1="39" y1="35" x2="35" y2="39" stroke="#ef4444" stroke-width="2.5" stroke-linecap="round"/>
</svg>

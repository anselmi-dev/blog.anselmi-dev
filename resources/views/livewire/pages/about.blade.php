@section('header-content')
    <x-hero-content>
        <h1 class="text-3xl font-bold tracking-tight text-zinc-900 sm:text-4xl md:text-5xl">
            <span class="block">{{ __('Hola') }}</span>
            <span class="mt-1 block">Carlos Anselmi</span>
        </h1>
        <p class="mt-3 font-folio-mono text-xs font-medium uppercase tracking-widest text-zinc-600 sm:text-sm">
            {{ __('Desarrollador fullstack') }}
        </p>
        <p class="mt-4 max-w-2xl text-base leading-relaxed text-zinc-600 sm:text-lg">
            {{ __('Ayudo a construir productos web rápidos y confiables con Laravel & Livewire') }}
        </p>
    </x-hero-content>
@endsection

<div class="pb-8 pt-2 sm:pb-12">
    <x-home.intro-columns />
    <x-home.intro-projects :projects="$projects" />
</div>

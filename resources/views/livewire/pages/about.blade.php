@section('header-content')
    <x-hero-content>
        <p class="mb-2 font-folio-mono text-xs font-medium uppercase tracking-widest text-zinc-600">
            Perfil
        </p>
        <h1 class="text-3xl font-bold tracking-tight text-zinc-900 sm:text-4xl">
            Sobre mí
        </h1>
        <div class="mt-4 max-w-2xl space-y-4 text-base leading-relaxed text-zinc-600 sm:text-lg">
            <p>
                Soy desarrollador web: diseño y construyo productos digitales con foco en claridad, performance y que el equipo pueda seguir iterando sin drama.
            </p>
            <p>
                Fuera del editor, me recargo con fotografía — mirar luz, encuadre y ritmo me ayuda tanto como un buen commit — y casi siempre con una Coca-Cola a mano (el café a veces traiciona; ella, no).
            </p>
            <p>
                Acá va un resumen de cómo trabajo, qué herramientas uso y un poco de lo que soy cuando no estoy mergeando PRs.
            </p>
        </div>
    </x-hero-content>
@endsection

<div class="pb-8 pt-2 sm:pb-12">
    <x-home.intro-columns />
</div>

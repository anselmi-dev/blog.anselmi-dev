{{-- Columna "Vista" de la tabla de ítems de galería. --}}
<div class="px-3 py-2">
    <x-admin.gallery-preview :preview="$getRecord()->adminPreview()" />
</div>

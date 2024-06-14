<div {{ $attributes->merge([
    'class' => "mx-auto max-w-full _lg:max-w-7xl _ relative"
]) }}>
    <!-- We've used 3xl here, but feel free to try other max-widths based on your needs -->
    <div class="mx-auto">
        {{ $slot }}
    </div>
</div>

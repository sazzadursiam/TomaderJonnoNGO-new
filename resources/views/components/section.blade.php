@props(['heading' => null, 'intro' => null, 'muted' => false])

<section {{ $attributes->class(['py-12 sm:py-16', 'bg-surface' => $muted]) }}>
    <div class="mx-auto max-w-7xl px-4">
        @if ($heading)
            <h2 class="text-2xl font-extrabold text-brand sm:text-3xl">{{ $heading }}</h2>
        @endif
        @if ($intro)
            <p class="mt-2 max-w-3xl text-gray-600">{{ $intro }}</p>
        @endif
        <div @class(['mt-8' => $heading || $intro])>
            {{ $slot }}
        </div>
    </div>
</section>

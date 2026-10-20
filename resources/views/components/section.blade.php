@props(['heading' => null, 'intro' => null, 'muted' => false])

<section {{ $attributes->class(['py-12 sm:py-16', 'bg-surface' => $muted]) }}>
    <div class="mx-auto max-w-7xl px-4">
        @if ($heading || $intro)
            <div data-reveal>
                @if ($heading)
                    <h2 class="text-2xl font-extrabold text-brand sm:text-3xl">{{ $heading }}</h2>
                    <span aria-hidden="true" class="heading-bar mt-3 block h-1 w-14 rounded-full bg-accent"></span>
                @endif
                @if ($intro)
                    <p class="mt-3 max-w-3xl text-gray-600">{{ $intro }}</p>
                @endif
            </div>
        @endif
        <div @class(['mt-8' => $heading || $intro])>
            {{ $slot }}
        </div>
    </div>
</section>

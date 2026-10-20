@props(['title', 'intro' => null, 'trail' => []])

{{-- $trail: list of [label, url|null] shown between "Home" and the current page. --}}
<div class="relative overflow-hidden border-b bg-linear-to-br from-brand-soft to-white">
    <div aria-hidden="true" class="pointer-events-none absolute -top-20 -right-16" data-parallax="0.25">
        <div class="size-64 animate-float rounded-full bg-emerald-200/40 blur-3xl"></div>
    </div>
    <div aria-hidden="true" class="pointer-events-none absolute -bottom-24 left-1/4" data-parallax="-0.15">
        <div class="size-48 animate-float-slow rounded-full bg-red-100/50 blur-3xl"></div>
    </div>
    <div class="relative mx-auto max-w-7xl px-4 py-10">
        <nav aria-label="{{ __('Breadcrumb') }}" class="mb-2 animate-fade-in text-xs text-gray-500">
            <ol class="flex flex-wrap items-center gap-1">
                <li><a href="{{ localized_route('home') }}" class="hover:text-accent">{{ __('Home') }}</a></li>
                @foreach ($trail as [$label, $url])
                    <li aria-hidden="true">/</li>
                    <li><a href="{{ $url }}" class="hover:text-accent">{{ $label }}</a></li>
                @endforeach
                <li aria-hidden="true">/</li>
                <li aria-current="page">{{ $title }}</li>
            </ol>
        </nav>
        <h1 class="animate-fade-up text-3xl font-extrabold text-brand sm:text-4xl">{{ $title }}</h1>
        <span aria-hidden="true" class="heading-bar mt-3 block h-1 w-16 rounded-full bg-accent" data-draw></span>
        @if ($intro)
            <p class="mt-3 max-w-3xl animate-fade-up text-gray-700 [animation-delay:120ms]">{{ $intro }}</p>
        @endif
    </div>
</div>

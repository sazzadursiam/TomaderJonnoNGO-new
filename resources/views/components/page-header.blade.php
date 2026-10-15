@props(['title', 'intro' => null, 'trail' => []])

{{-- $trail: list of [label, url|null] shown between "Home" and the current page. --}}
<div class="border-b bg-gradient-to-br from-brand-soft to-white">
    <div class="mx-auto max-w-7xl px-4 py-10">
        <nav aria-label="{{ __('Breadcrumb') }}" class="mb-2 text-xs text-gray-500">
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
        <h1 class="text-3xl font-extrabold text-brand sm:text-4xl">{{ $title }}</h1>
        @if ($intro)
            <p class="mt-3 max-w-3xl text-gray-700">{{ $intro }}</p>
        @endif
    </div>
</div>

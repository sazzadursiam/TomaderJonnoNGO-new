@props(['title' => null, 'description' => null])

@php
    use App\Support\Localization;

    $siteName = __('Tomader Jonno');
    $pageTitle = $title ? "{$title} | {$siteName}" : "{$siteName} | ".__('Helping people grow, building better communities.');
    $metaDescription = $description ?? __('Tomader Jonno is a welfare, non-profit, voluntary NGO in Bangladesh working in education, rehabilitation, women\'s empowerment and sustainable development.');
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $pageTitle }}</title>
    <meta name="description" content="{{ $metaDescription }}">

    <link rel="canonical" href="{{ url()->current() }}">
    @foreach (array_keys(Localization::LOCALES) as $code)
        <link rel="alternate" hreflang="{{ $code }}" href="{{ Localization::switchUrl($code) }}">
    @endforeach
    <link rel="alternate" hreflang="x-default" href="{{ Localization::switchUrl(Localization::DEFAULT_LOCALE) }}">

    <meta property="og:type" content="website">
    <meta property="og:site_name" content="{{ $siteName }}">
    <meta property="og:title" content="{{ $pageTitle }}">
    <meta property="og:description" content="{{ $metaDescription }}">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="{{ asset('images/logo.png') }}">
    <meta property="og:locale" content="{{ app()->getLocale() === 'bn' ? 'bn_BD' : 'en_US' }}">

    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">

    <script>document.documentElement.classList.add('js')</script>

    @fonts
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-white font-sans text-ink antialiased">
    <a href="#main" class="sr-only focus:not-sr-only focus:fixed focus:top-2 focus:left-2 focus:z-50 focus:rounded focus:bg-white focus:px-4 focus:py-2 focus:shadow">
        {{ __('Skip to content') }}
    </a>

    <x-site.topbar />
    <x-site.header />
    <x-site.drawer />

    <main id="main">
        {{ $slot }}
    </main>

    <x-site.footer />

    <button type="button" data-back-to-top tabindex="-1" aria-label="{{ __('Back to top') }}"
            class="pointer-events-none fixed right-4 bottom-4 z-30 flex size-12 translate-y-4 items-center justify-center rounded-full bg-brand text-white opacity-0 shadow-lg transition duration-300 hover:-translate-y-1 hover:bg-brand-dark">
        <x-ui.icon name="chevron-down" class="size-6 rotate-180" />
    </button>
</body>
</html>

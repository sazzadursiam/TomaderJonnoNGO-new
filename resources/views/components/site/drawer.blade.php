@php
    $groups = [
        __('Who We Are') => [
            [__('About Us'), localized_route('about')],
            [__('Management Team'), localized_route('team')],
        ],
        __('What We Do') => [
            [__('All Programs'), localized_route('programs.index')],
            ...$navPrograms->map(fn ($program) => [$program->translate('title'), localized_route('programs.show', $program)])->all(),
        ],
    ];
@endphp

<div id="drawer-overlay" class="fixed inset-0 z-50 hidden bg-black/50"></div>

<aside id="drawer" aria-hidden="true" aria-label="{{ __('Menu') }}"
       class="fixed top-0 right-0 bottom-0 z-50 w-72 max-w-[85%] translate-x-full overflow-y-auto bg-white p-5 shadow-2xl transition-transform duration-300">
    <div class="mb-3 flex items-center justify-between">
        <img src="{{ asset('images/logo.png') }}" alt="{{ __('Tomader Jonno') }}" class="h-12 w-auto">
        <button type="button" class="flex size-11 items-center justify-center" data-drawer-close aria-label="{{ __('Close menu') }}">
            <x-ui.icon name="close" class="size-6" />
        </button>
    </div>

    <nav class="font-semibold" aria-label="{{ __('Mobile navigation') }}">
        <a href="{{ localized_route('home') }}" class="block border-b py-3">{{ __('Home') }}</a>
        @foreach ($groups as $label => $links)
            <details class="group border-b">
                <summary class="flex cursor-pointer items-center justify-between py-3">
                    {{ $label }} <x-ui.icon name="chevron-down" class="size-4 transition group-open:rotate-180" />
                </summary>
                <div class="space-y-1 pb-3 pl-3 font-normal">
                    @foreach ($links as [$text, $url])
                        <a href="{{ $url }}" class="block py-1.5">{{ $text }}</a>
                    @endforeach
                </div>
            </details>
        @endforeach
        <a href="{{ localized_route('stories.index') }}" class="block border-b py-3">{{ __('Projects') }}</a>
        <a href="{{ localized_route('contact') }}" class="block border-b py-3">{{ __('Contact') }}</a>
    </nav>

    <div class="mt-4 space-y-1 text-sm text-gray-600">
        @if (filled($site['phone'] ?? null))
            <p class="flex items-center gap-2"><x-ui.icon name="phone" class="size-4" /> {{ $site['phone'] }}</p>
        @endif
        @if (filled($site['email'] ?? null))
            <p class="flex items-center gap-2"><x-ui.icon name="mail" class="size-4" /> {{ $site['email'] }}</p>
        @endif
    </div>

    <x-site.language-switch class="mt-4 border border-brand text-sm [&>a]:py-2.5" />

    <a href="{{ localized_route('donate') }}" class="btn-donate mt-4 w-full">{{ __('Donate Now') }}</a>
</aside>

@php
    $isActive = fn (string ...$names): bool => collect($names)->contains(fn (string $name) => request()->routeIs($name, "*.{$name}"));
    $linkClass = fn (bool $active): string => 'flex items-center gap-1 px-3 py-2 '.($active ? 'text-accent border-b-2 border-accent' : 'hover:text-accent');
    $dropdownClass = 'invisible absolute left-0 top-full z-50 min-w-56 rounded-b border-t-2 border-brand bg-white py-2 opacity-0 shadow-lg transition group-hover:visible group-hover:opacity-100 group-focus-within:visible group-focus-within:opacity-100';
@endphp

<header class="sticky top-0 z-40 bg-white shadow">
    <nav class="mx-auto flex h-16 max-w-7xl items-center justify-between px-4" aria-label="{{ __('Main navigation') }}">
        <a href="{{ localized_route('home') }}" class="shrink-0">
            <img src="{{ asset('images/logo.png') }}" alt="{{ __('Tomader Jonno home') }}" class="h-12 w-auto" width="305" height="360">
        </a>

        <ul class="hidden items-center gap-1 text-sm font-semibold lg:flex">
            <li>
                <a href="{{ localized_route('home') }}" class="{{ $linkClass($isActive('home')) }}" @if ($isActive('home')) aria-current="page" @endif>{{ __('Home') }}</a>
            </li>
            <li class="group relative">
                <a href="{{ localized_route('about') }}" class="{{ $linkClass($isActive('about', 'team')) }}">
                    {{ __('Who We Are') }} <x-ui.icon name="chevron-down" class="size-4" />
                </a>
                <div class="{{ $dropdownClass }}">
                    <a href="{{ localized_route('about') }}" class="block px-4 py-2.5 hover:bg-brand-soft focus:bg-brand-soft">{{ __('About Us') }}</a>
                    <a href="{{ localized_route('team') }}" class="block px-4 py-2.5 hover:bg-brand-soft focus:bg-brand-soft">{{ __('Management Team') }}</a>
                </div>
            </li>
            <li class="group relative">
                <a href="{{ localized_route('programs.index') }}" class="{{ $linkClass($isActive('programs.index', 'programs.show')) }}">
                    {{ __('What We Do') }} <x-ui.icon name="chevron-down" class="size-4" />
                </a>
                <div class="{{ $dropdownClass }} w-72">
                    @foreach ($navPrograms as $navProgram)
                        <a href="{{ localized_route('programs.show', $navProgram) }}" class="flex items-center gap-2 px-4 py-2.5 hover:bg-brand-soft focus:bg-brand-soft">
                            <x-ui.icon :name="$navProgram->icon" class="size-4 text-brand" /> {{ $navProgram->translate('title') }}
                        </a>
                    @endforeach
                </div>
            </li>
            <li>
                <a href="{{ localized_route('stories.index') }}" class="{{ $linkClass($isActive('stories.index', 'stories.show')) }}">{{ __('Projects') }}</a>
            </li>
            <li>
                <a href="{{ localized_route('contact') }}" class="{{ $linkClass($isActive('contact')) }}">{{ __('Contact') }}</a>
            </li>
        </ul>

        <div class="flex items-center gap-2">
            <a href="{{ localized_route('donate') }}" class="hidden rounded bg-accent px-5 py-2.5 text-sm font-bold text-white hover:bg-accent-dark lg:block">{{ __('Donate Now') }}</a>
            <button type="button" class="flex size-11 items-center justify-center text-brand lg:hidden" data-drawer-open aria-controls="drawer" aria-expanded="false" aria-label="{{ __('Open menu') }}">
                <x-ui.icon name="menu" class="size-7" />
            </button>
        </div>
    </nav>
</header>

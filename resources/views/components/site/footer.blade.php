@php
    $socials = collect([
        'facebook' => ['Facebook', $site['facebook_url'] ?? null],
        'youtube' => ['YouTube', $site['youtube_url'] ?? null],
        'linkedin' => ['LinkedIn', $site['linkedin_url'] ?? null],
        'x' => ['X', $site['x_url'] ?? null],
    ])->filter(fn (array $social) => filled($social[1]));
@endphp

<footer class="bg-brand-dark text-emerald-50">
    <div class="mx-auto grid max-w-7xl gap-10 px-4 py-12 md:grid-cols-3">
        <div>
            <span class="inline-block rounded-lg bg-white p-2">
                <img src="{{ asset('images/logo.png') }}" alt="{{ __('Tomader Jonno') }}" class="h-12 w-auto" loading="lazy">
            </span>
            <p class="mt-4 text-sm text-emerald-100">
                {{ __('Tomader Jonno is a welfare, non-profit, voluntary NGO in Bangladesh. Our mission is to bring smiling faces to underprivileged people through education, rehabilitation, women\'s empowerment and sustainable development.') }}
            </p>
            @if ($socials->isNotEmpty())
                <div class="mt-4 flex gap-2">
                    @foreach ($socials as $icon => [$label, $url])
                        <a href="{{ $url }}" target="_blank" rel="noopener" aria-label="{{ $label }}"
                           class="flex size-11 items-center justify-center rounded-full bg-emerald-800 hover:bg-accent">
                            <x-ui.icon :name="$icon" class="size-5" />
                        </a>
                    @endforeach
                </div>
            @endif
        </div>

        <div>
            <h3 class="mb-3 font-bold text-white">{{ __('Quick Links') }}</h3>
            <ul class="space-y-1 text-sm">
                <li><a class="block py-1.5 hover:text-white" href="{{ localized_route('about') }}">{{ __('About Us') }}</a></li>
                <li><a class="block py-1.5 hover:text-white" href="{{ localized_route('programs.index') }}">{{ __('What We Do') }}</a></li>
                <li><a class="block py-1.5 hover:text-white" href="{{ localized_route('stories.index') }}">{{ __('Projects') }}</a></li>
                <li><a class="block py-1.5 hover:text-white" href="{{ localized_route('donate') }}">{{ __('Donate') }}</a></li>
                <li><a class="block py-1.5 hover:text-white" href="{{ localized_route('contact') }}">{{ __('Contact') }}</a></li>
            </ul>
        </div>

        <div>
            <h3 class="mb-3 font-bold text-white">{{ __('Contact') }}</h3>
            <div class="space-y-2 text-sm">
                @if (filled($site['phone'] ?? null))
                    <a href="tel:{{ preg_replace('/[^0-9+]/', '', $site['phone']) }}" class="flex items-center gap-2 py-1 hover:text-white"><x-ui.icon name="phone" class="size-4" /> {{ $site['phone'] }}</a>
                @endif
                @if (filled($site['email'] ?? null))
                    <a href="mailto:{{ $site['email'] }}" class="flex items-center gap-2 py-1 hover:text-white"><x-ui.icon name="mail" class="size-4" /> {{ $site['email'] }}</a>
                @endif
                @if (filled(translated($site['address'] ?? null)))
                    <p class="flex items-start gap-2 py-1"><x-ui.icon name="map-pin" class="mt-0.5 size-4" /> {{ translated($site['address']) }}</p>
                @endif
            </div>
        </div>
    </div>

    <div class="mx-auto max-w-7xl border-t border-emerald-800 px-4 py-4 text-xs">
        © {{ \App\Support\Localization::digits((string) now()->year) }} {{ __('Tomader Jonno, All Rights Reserved') }}
    </div>
</footer>

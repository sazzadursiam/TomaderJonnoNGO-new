<x-layouts.app>
    <section class="relative isolate overflow-hidden bg-linear-to-br from-brand-soft to-white">
        <div aria-hidden="true" class="pointer-events-none absolute inset-0 -z-10">
            <div class="absolute -top-24 -left-24" data-parallax="0.3">
                <div class="size-72 animate-float rounded-full bg-emerald-200/50 blur-3xl"></div>
            </div>
            <div class="absolute top-1/3 -right-24" data-parallax="0.15">
                <div class="size-96 animate-float-slow rounded-full bg-red-100/60 blur-3xl"></div>
            </div>
            <div class="absolute -bottom-20 left-1/3" data-parallax="-0.1">
                <div class="size-64 animate-float rounded-full bg-emerald-100/70 blur-3xl [animation-delay:-4s]"></div>
            </div>
        </div>

        <div class="mx-auto grid max-w-7xl items-center gap-10 px-4 py-10 lg:grid-cols-2 lg:py-16">
            <div>
                <span class="mb-4 inline-flex animate-fade-up items-center gap-2 rounded-full bg-brand px-3 py-1 text-xs font-semibold text-white">
                    <x-ui.icon name="heart" class="size-3.5 animate-heartbeat text-red-300" />
                    {{ __('Helping people grow, building better communities.') }}
                </span>
                <h1 class="animate-fade-up text-3xl leading-tight font-extrabold text-brand [animation-delay:120ms] sm:text-4xl lg:text-5xl">
                    {{ __('Empowering Communities, Fighting Poverty, and Building a Dignified Society Locally.') }}
                </h1>
                @php
                    $rotatingWords = [__('children\'s education'), __('women\'s livelihoods'), __('inclusive communities'), __('a greener tomorrow')];
                @endphp
                <p class="mt-4 animate-fade-up text-lg font-semibold text-gray-700 [animation-delay:200ms] sm:text-xl"
                   data-typewriter data-words='@json($rotatingWords)'>
                    {{ __('Together for') }}
                    <span class="text-accent" data-typewriter-text>{{ $rotatingWords[0] }}</span><span class="typewriter-caret text-accent" aria-hidden="true"></span>
                </p>
                <div class="mt-6 flex animate-fade-up flex-wrap gap-3 [animation-delay:260ms]">
                    <a href="{{ localized_route('donate') }}" class="btn-donate">
                        <x-ui.icon name="heart" class="size-4" /> {{ __('Donate Now') }}
                    </a>
                    <a href="{{ localized_route('programs.index') }}" class="btn-outline group">
                        {{ __('Explore Our Programs') }}
                        <x-ui.icon name="arrow-right" class="size-4 transition-transform group-hover:translate-x-1" />
                    </a>
                </div>
            </div>

            <x-quick-give id="give" :tiers="$donationTiers" class="animate-slide-in-right [animation-delay:200ms]" />
        </div>
    </section>

    @if ($stats->isNotEmpty())
        <section class="relative overflow-hidden bg-brand text-white" aria-label="{{ __('Our impact') }}">
            <div aria-hidden="true" class="pointer-events-none absolute inset-0 bg-[radial-gradient(circle_at_20%_20%,rgb(255_255_255/0.08),transparent_40%),radial-gradient(circle_at_80%_80%,rgb(255_255_255/0.06),transparent_40%)]"></div>
            <dl class="relative mx-auto grid max-w-7xl grid-cols-2 gap-6 px-4 py-10 text-center lg:grid-cols-4" data-reveal-stagger>
                @foreach ($stats as $stat)
                    <div class="group flex flex-col-reverse items-center rounded-xl px-2 py-3 transition duration-300 hover:bg-white/5" data-stat>
                        <dt class="mt-3 text-sm text-emerald-100">{{ $stat->translate('label') }}</dt>
                        <dd class="relative flex size-32 items-center justify-center sm:size-36">
                            <svg class="absolute inset-0 size-full -rotate-90 transition-transform duration-500 group-hover:scale-105" viewBox="0 0 120 120" aria-hidden="true">
                                <circle cx="60" cy="60" r="54" fill="none" stroke="currentColor" stroke-width="6" class="text-white/10" />
                                <circle cx="60" cy="60" r="54" fill="none" stroke="currentColor" stroke-width="6" stroke-linecap="round"
                                        pathLength="1" stroke-dasharray="1" stroke-dashoffset="0" class="text-red-300" data-count-ring />
                            </svg>
                            <span class="relative">
                                <span class="text-2xl font-extrabold tabular-nums sm:text-3xl" data-count="{{ $stat->value }}">{{ local_number($stat->value) }}</span><span class="text-xl font-bold text-red-300 sm:text-2xl">{{ $stat->suffix }}</span>
                            </span>
                        </dd>
                    </div>
                @endforeach
            </dl>
        </section>
    @endif

    <x-section :heading="__('What We Do')" :intro="__('Grassroots work in education, rehabilitation, women\'s empowerment, and community development.')" muted>
        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4" data-reveal-stagger>
            @foreach ($programs as $program)
                <x-program-card :program="$program" />
            @endforeach
        </div>
    </x-section>

    @php
        $steps = [
            ['heart', __('You Give'), __('Choose a monthly or one-time gift — every taka counts.')],
            ['handshake', __('We Act'), __('Our field teams turn your gift into classes, training, therapy and trees.')],
            ['sparkles', __('Lives Change'), __('Children learn, women earn and communities grow stronger.')],
        ];
    @endphp
    <x-section :heading="__('How Your Donation Helps')" :intro="__('Every gift follows a clear path from your hands to the field.')">
        <div class="relative">
            <div aria-hidden="true" class="draw-line pointer-events-none absolute top-10 left-[16%] hidden w-[68%] border-t-2 border-dashed border-accent/50 md:block"></div>
            <ol class="relative grid gap-8 md:grid-cols-3" data-reveal-stagger>
                @foreach ($steps as $index => [$icon, $title, $text])
                    <li class="group text-center">
                        <span class="relative mx-auto flex size-20 items-center justify-center rounded-full bg-white text-brand shadow-lg ring-8 ring-brand-soft transition duration-300 group-hover:-translate-y-1 group-hover:bg-brand group-hover:text-white">
                            <x-ui.icon :name="$icon" class="size-8 transition-transform duration-300 group-hover:scale-110" />
                            <span class="absolute -top-1 -right-1 flex size-7 items-center justify-center rounded-full bg-accent text-xs font-bold text-white">{{ local_number($index + 1) }}</span>
                        </span>
                        <h3 class="mt-5 text-lg font-bold text-brand">{{ $title }}</h3>
                        <p class="mx-auto mt-2 max-w-xs text-sm text-gray-600">{{ $text }}</p>
                    </li>
                @endforeach
            </ol>
        </div>
        <div class="mt-10 text-center" data-reveal>
            <a href="{{ localized_route('donate') }}" class="btn-donate"><x-ui.icon name="heart" class="size-4" /> {{ __('Donate Now') }}</a>
        </div>
    </x-section>

    @if ($stories->isNotEmpty())
        <x-section :heading="__('Impact Stories from the Field')" :intro="__('Firsthand accounts of resilience, skill growth and community change.')">
            <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3" data-reveal-stagger>
                @foreach ($stories as $story)
                    <x-story-card :story="$story" />
                @endforeach
            </div>
            <div class="mt-8 text-center" data-reveal>
                <a href="{{ localized_route('stories.index') }}" class="btn-outline">{{ __('See All Stories') }}</a>
            </div>
        </x-section>
    @endif

    <x-donate-banner :message="__('Join hands to expand our reach this season.')" />
</x-layouts.app>

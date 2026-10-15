<x-layouts.app>
    <section class="bg-gradient-to-br from-brand-soft to-white">
        <div class="mx-auto grid max-w-7xl items-center gap-10 px-4 py-10 lg:grid-cols-2 lg:py-16">
            <div>
                <span class="mb-4 inline-block rounded-full bg-brand px-3 py-1 text-xs font-semibold text-white">
                    {{ __('Helping people grow, building better communities.') }}
                </span>
                <h1 class="text-3xl leading-tight font-extrabold text-brand sm:text-4xl lg:text-5xl">
                    {{ __('Empowering Communities, Fighting Poverty, and Building a Dignified Society Locally.') }}
                </h1>
                <div class="mt-6 flex flex-wrap gap-3">
                    <a href="{{ localized_route('donate') }}" class="btn-donate">{{ __('Donate Now') }}</a>
                    <a href="{{ localized_route('programs.index') }}" class="btn-outline">{{ __('Explore Our Programs') }}</a>
                </div>
            </div>

            <x-quick-give id="give" :tiers="$donationTiers" />
        </div>
    </section>

    @if ($stats->isNotEmpty())
        <section class="bg-brand text-white" aria-label="{{ __('Our impact') }}">
            <dl class="mx-auto grid max-w-7xl grid-cols-2 gap-6 px-4 py-8 text-center lg:grid-cols-4">
                @foreach ($stats as $stat)
                    <div class="flex flex-col-reverse">
                        <dt class="mt-1 text-sm text-emerald-100">{{ $stat->translate('label') }}</dt>
                        <dd>
                            <span class="text-4xl font-extrabold" data-count="{{ $stat->value }}">{{ local_number($stat->value) }}</span><span class="text-3xl font-bold text-red-300">{{ $stat->suffix }}</span>
                        </dd>
                    </div>
                @endforeach
            </dl>
        </section>
    @endif

    <x-section :heading="__('What We Do')" :intro="__('Grassroots work in education, rehabilitation, women\'s empowerment, and community development.')" muted>
        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
            @foreach ($programs as $program)
                <x-program-card :program="$program" />
            @endforeach
        </div>
    </x-section>

    @if ($stories->isNotEmpty())
        <x-section :heading="__('Impact Stories from the Field')" :intro="__('Firsthand accounts of resilience, skill growth and community change.')">
            <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($stories as $story)
                    <x-story-card :story="$story" />
                @endforeach
            </div>
            <div class="mt-8 text-center">
                <a href="{{ localized_route('stories.index') }}" class="btn-outline">{{ __('See All Stories') }}</a>
            </div>
        </x-section>
    @endif

    <x-donate-banner :message="__('Join hands to expand our reach this season.')" />
</x-layouts.app>

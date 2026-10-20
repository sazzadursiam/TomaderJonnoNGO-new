@php
    $title = $program->pageTitle();
    $focusAreas = $program->focus_areas ?? [];
    $wings = $program->wings ?? [];
@endphp

<x-layouts.app :title="$title" :description="$program->translate('summary')">
    <x-page-header :title="$title" :intro="$program->translate('description')"
                   :trail="[[__('What We Do'), localized_route('programs.index')]]" />

    @if (filled($program->translate('overview')))
        <x-section :heading="__('Program Overview')">
            <div class="grid items-center gap-8 lg:grid-cols-2">
                <x-photo :src="$program->image" :alt="$title" :icon="$program->icon" class="h-72 lg:h-96" data-reveal />
                <p class="text-lg text-gray-700" data-reveal>{{ $program->translate('overview') }}</p>
            </div>
        </x-section>
    @endif

    @if ($focusAreas !== [])
        <x-section :heading="__('Focus Areas')">
            <div class="grid items-center gap-8 lg:grid-cols-2">
                <x-photo :src="$program->image" :alt="$title" :icon="$program->icon" class="h-72 lg:h-96" data-reveal />
                <ul class="space-y-4" data-reveal-stagger>
                    @foreach ($focusAreas as $area)
                        <li class="group flex gap-3 rounded-lg p-2 transition hover:bg-brand-soft">
                            <span class="mt-1 flex size-6 shrink-0 items-center justify-center rounded-full bg-brand text-white transition-transform duration-300 group-hover:scale-110">
                                <x-ui.icon name="check" class="size-3.5" />
                            </span>
                            <div>
                                <h3 class="font-bold text-brand">{{ translated($area['title'] ?? null) }}</h3>
                                <p class="text-sm text-gray-600">{{ translated($area['text'] ?? null) }}</p>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
        </x-section>
    @endif

    @foreach ($wings as $wing)
        @php
            $wingTitle = translated($wing['title'] ?? null);
            $images = array_values(array_filter($wing['images'] ?? []));
            $slots = $images === [] ? 2 : count($images);
        @endphp
        <x-section :heading="$wingTitle" :intro="translated($wing['text'] ?? null)" :muted="$loop->odd">
            @if (filled($wing['label'] ?? null))
                <p class="-mt-6 mb-6 text-xs font-bold uppercase tracking-wide text-accent">{{ $wing['label'] }}</p>
            @endif
            <div data-reveal-stagger @class([
                'grid gap-3',
                'sm:grid-cols-2' => $slots === 2,
                'sm:grid-cols-3' => $slots === 3,
                'grid-cols-2 lg:grid-cols-4' => $slots >= 4,
            ])>
                @for ($index = 0; $index < $slots; $index++)
                    <x-photo :src="$images[$index] ?? null" :alt="$wingTitle" :icon="$program->icon" :class="$slots === 1 ? 'h-56 sm:h-80' : 'h-44 sm:h-56'" />
                @endfor
            </div>
        </x-section>
    @endforeach

    <x-donate-banner :message="$program->icon === 'women' ? __('Help a woman build her own livelihood.') : __('Support this program with a gift today.')" />
</x-layouts.app>

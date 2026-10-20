<x-layouts.app :title="__('Management Team')" :description="__('The people guiding Tomader Jonno\'s work with transparency and care.')">
    <x-page-header :title="__('Management Team')" :intro="__('The people guiding Tomader Jonno\'s work with transparency and care.')"
                   :trail="[[__('Who We Are'), localized_route('about')]]" />

    <x-section :heading="__('Our Leadership')">
        @if ($members->isEmpty())
            <p class="text-gray-600">{{ __('Team profiles will be added soon.') }}</p>
        @else
            <ul class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4" data-reveal-stagger>
                @foreach ($members as $member)
                    @php
                        $name = $member->translate('name');
                        $role = $member->translate('role');
                    @endphp
                    <li class="group rounded-xl border bg-white p-5 text-center transition duration-300 hover:-translate-y-1.5 hover:border-brand/30 hover:shadow-xl">
                        @if ($member->photo)
                            <img src="{{ media_url($member->photo) }}" alt="{{ $name ?: $role }}" loading="lazy"
                                 class="mx-auto size-24 rounded-full object-cover ring-4 ring-brand-soft transition duration-300 group-hover:scale-105 group-hover:ring-brand/30">
                        @else
                            <div class="mx-auto flex size-24 items-center justify-center rounded-full bg-linear-to-br from-brand to-emerald-500 text-white">
                                <x-ui.icon name="user" class="size-10" />
                            </div>
                        @endif
                        @if (filled($name))
                            <h3 class="mt-4 font-bold text-brand">{{ $name }}</h3>
                            <p class="text-sm font-semibold text-accent">{{ $role }}</p>
                        @else
                            <h3 class="mt-4 font-bold text-accent">{{ $role }}</h3>
                        @endif
                        @if (filled($member->translate('bio')))
                            <p class="mt-1 text-xs text-gray-600">{{ $member->translate('bio') }}</p>
                        @endif
                    </li>
                @endforeach
            </ul>
        @endif
    </x-section>

    <x-donate-banner :message="__('Support the team on the ground.')" />
</x-layouts.app>

<x-layouts.app :title="__('Management Team')" :description="__('The people guiding Tomader Jonno\'s work with transparency and care.')">
    <x-page-header :title="__('Management Team')" :intro="__('The people guiding Tomader Jonno\'s work with transparency and care.')"
                   :trail="[[__('Who We Are'), localized_route('about')]]" />

    <x-section :heading="__('Our Leadership')">
        @if ($members->isEmpty())
            <p class="text-gray-600">{{ __('Team profiles will be added soon.') }}</p>
        @else
            <ul class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
                @foreach ($members as $member)
                    <li class="rounded-xl border p-5 text-center transition hover:shadow-lg">
                        @if ($member->photo)
                            <img src="{{ media_url($member->photo) }}" alt="{{ $member->translate('name') }}" loading="lazy"
                                 class="mx-auto size-24 rounded-full object-cover">
                        @else
                            <div class="mx-auto flex size-24 items-center justify-center rounded-full bg-gradient-to-br from-brand to-emerald-500 text-white">
                                <x-ui.icon name="user" class="size-10" />
                            </div>
                        @endif
                        <h3 class="mt-4 font-bold text-brand">{{ $member->translate('name') }}</h3>
                        <p class="text-sm font-semibold text-accent">{{ $member->translate('role') }}</p>
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

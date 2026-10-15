@php
    $values = [
        ['eye', __('Transparency')],
        ['shield', __('Dignity')],
        ['handshake', __('Community Collaboration')],
        ['scale', __('Equal Opportunity')],
    ];
@endphp

<x-layouts.app :title="__('About Us')" :description="__('Vision, mission and values of Tomader Jonno.')">
    <x-page-header :title="__('About Us')" :intro="__('Vision, mission and values of Tomader Jonno.')"
                   :trail="[[__('Who We Are'), localized_route('about')]]" />

    <x-section :heading="__('About Tomader Jonno')">
        <div class="grid items-center gap-8 lg:grid-cols-2">
            <p class="text-lg text-gray-700">
                {{ __('Tomader Jonno (TJ) is a welfare, non-profit, voluntary, non-governmental organization (NGO) dedicated to uplifting underprivileged populations, protecting the environment and fostering sustainable community growth across Bangladesh. Rooted in grassroots action, TJ has driven local interventions including seasonal welfare distributions, healthcare camps, tree plantation and foundational education.') }}
            </p>
            <x-photo :alt="__('Community circle')" icon="handshake" class="h-64 lg:h-80" />
        </div>
    </x-section>

    <section class="bg-surface py-12 sm:py-16">
        <div class="mx-auto grid max-w-7xl gap-6 px-4 md:grid-cols-2">
            <div class="rounded-xl border-t-4 border-brand bg-white p-6 shadow">
                <h2 class="text-xl font-bold text-brand">{{ __('Our Vision') }}</h2>
                <p class="mt-3 text-gray-700">{{ __('A world free from poverty, exploitation and discrimination where people live in freedom, dignity and human security.') }}</p>
            </div>
            <div class="rounded-xl border-t-4 border-accent bg-white p-6 shadow">
                <h2 class="text-xl font-bold text-brand">{{ __('Our Mission') }}</h2>
                <p class="mt-3 text-gray-700">{{ __('To combat poverty and marginalization through accessible education, specialized disability support, economic self-reliance via vocational and artisan training, and active grassroots mobilization — to bring smiling faces to underprivileged people.') }}</p>
            </div>
        </div>
    </section>

    <x-section :heading="__('Core Values')">
        <ul class="grid grid-cols-2 gap-4 lg:grid-cols-4">
            @foreach ($values as [$icon, $label])
                <li class="rounded-xl border p-5 text-center">
                    <x-ui.icon :name="$icon" class="mx-auto size-8 text-brand" />
                    <span class="mt-2 block font-bold text-brand">{{ $label }}</span>
                </li>
            @endforeach
        </ul>
    </x-section>

    <x-donate-banner :message="__('Be part of our story.')" />
</x-layouts.app>

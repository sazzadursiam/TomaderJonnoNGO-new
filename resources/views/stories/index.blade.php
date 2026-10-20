<x-layouts.app :title="__('Projects & Stories')" :description="__('Field updates and success stories from our project areas.')">
    <x-page-header :title="__('Projects & Stories')" :intro="__('Field updates and success stories from our project areas.')" />

    <x-section :heading="__('Impact Stories from the Field')" :intro="__('Firsthand accounts of resilience, skill growth and community change.')">
        @if ($stories->isEmpty())
            <p class="text-gray-600">{{ __('Stories will be published soon.') }}</p>
        @else
            <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3" data-reveal-stagger>
                @foreach ($stories as $story)
                    <x-story-card :story="$story" />
                @endforeach
            </div>
            <div class="mt-8">{{ $stories->links() }}</div>
        @endif
    </x-section>

    <x-donate-banner :message="__('Help us write the next success story.')" />
</x-layouts.app>

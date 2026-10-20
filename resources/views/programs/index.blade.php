<x-layouts.app :title="__('What We Do')" :description="__('Four core programs working side by side with local communities.')">
    <x-page-header :title="__('What We Do')" :intro="__('Four core programs working side by side with local communities.')" />

    <x-section :heading="__('Our Programs')">
        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4" data-reveal-stagger>
            @foreach ($programs as $program)
                <x-program-card :program="$program" />
            @endforeach
        </div>
    </x-section>

    <x-donate-banner :message="__('Join hands to expand our reach this season.')" />
</x-layouts.app>

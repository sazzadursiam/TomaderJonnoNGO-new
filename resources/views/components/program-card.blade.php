@props(['program'])

<a href="{{ localized_route('programs.show', $program) }}"
   class="group block rounded-xl border-b-4 border-transparent bg-white p-3 shadow transition hover:border-brand hover:shadow-xl focus-visible:border-brand focus-visible:outline-none">
    <x-photo :src="$program->image" :alt="$program->translate('title')" :icon="$program->icon" class="h-40" />
    <h3 class="mt-4 px-1 text-lg font-bold text-brand">{{ $program->translate('title') }}</h3>
    <p class="mt-1 px-1 text-sm text-gray-600">{{ $program->translate('summary') }}</p>
    <span class="mt-3 mb-1 inline-flex items-center gap-1 px-1 text-sm font-semibold text-accent">
        {{ __('Learn More') }} <x-ui.icon name="arrow-right" class="size-4 transition group-hover:translate-x-1" />
    </span>
</a>

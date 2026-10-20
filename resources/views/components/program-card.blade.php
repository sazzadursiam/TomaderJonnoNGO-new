@props(['program'])

<a href="{{ localized_route('programs.show', $program) }}"
   class="group block rounded-xl border-b-4 border-transparent bg-white p-3 shadow transition duration-300 hover:-translate-y-1.5 hover:border-brand hover:shadow-xl focus-visible:border-brand focus-visible:outline-none">
    <div class="relative">
        <x-photo :src="$program->image" :alt="$program->translate('title')" :icon="$program->icon" class="h-40" />
        <span class="absolute -bottom-5 left-4 flex size-11 items-center justify-center rounded-full bg-white text-brand shadow-md ring-4 ring-white transition duration-300 group-hover:rotate-12 group-hover:bg-brand group-hover:text-white">
            <x-ui.icon :name="$program->icon" class="size-5" />
        </span>
    </div>
    <h3 class="mt-7 px-1 text-lg font-bold text-brand">{{ $program->translate('title') }}</h3>
    <p class="mt-1 px-1 text-sm text-gray-600">{{ $program->translate('summary') }}</p>
    <span class="mt-3 mb-1 inline-flex items-center gap-1 px-1 text-sm font-semibold text-accent">
        {{ __('Learn More') }} <x-ui.icon name="arrow-right" class="size-4 transition-transform duration-300 group-hover:translate-x-1.5" />
    </span>
</a>

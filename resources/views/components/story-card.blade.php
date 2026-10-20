@props(['story'])

<article class="group relative rounded-xl bg-white p-3 shadow transition duration-300 hover:-translate-y-1.5 hover:shadow-xl">
    <div class="relative overflow-hidden rounded-xl">
        <x-photo :src="$story->image" :alt="$story->translate('title')" class="h-44" />
        <div aria-hidden="true"
             class="absolute inset-0 flex items-end bg-linear-to-t from-brand/90 via-brand/50 to-transparent p-4 opacity-0 transition duration-500 group-hover:opacity-100">
            <span class="inline-flex translate-y-4 items-center gap-1.5 text-sm font-bold text-white transition duration-500 group-hover:translate-y-0">
                {{ __('Read story') }} <x-ui.icon name="arrow-right" class="size-4" />
            </span>
        </div>
    </div>
    <p class="mt-4 px-1 text-xs font-bold uppercase text-accent">{{ $story->translate('category') }}</p>
    <h3 class="mt-1 px-1 font-bold">
        <a href="{{ localized_route('stories.show', $story) }}" class="transition-colors after:absolute after:inset-0 group-hover:text-brand">
            {{ $story->translate('title') }}
        </a>
    </h3>
    <p class="mt-2 px-1 pb-1 text-sm text-gray-600">{{ $story->translate('excerpt') }}</p>
</article>

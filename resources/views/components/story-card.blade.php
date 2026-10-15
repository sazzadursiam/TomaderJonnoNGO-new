@props(['story'])

<article class="group relative rounded-xl bg-white p-3 shadow transition hover:shadow-lg">
    <x-photo :src="$story->image" :alt="$story->translate('title')" class="h-44" />
    <p class="mt-4 px-1 text-xs font-bold uppercase text-accent">{{ $story->translate('category') }}</p>
    <h3 class="mt-1 px-1 font-bold">
        <a href="{{ localized_route('stories.show', $story) }}" class="after:absolute after:inset-0 group-hover:text-brand">
            {{ $story->translate('title') }}
        </a>
    </h3>
    <p class="mt-2 px-1 pb-1 text-sm text-gray-600">{{ $story->translate('excerpt') }}</p>
</article>

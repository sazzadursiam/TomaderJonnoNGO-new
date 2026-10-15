@php
    $title = $story->translate('title');
    $body = $story->translate('body');
@endphp

<x-layouts.app :title="$title" :description="$story->translate('excerpt')">
    <x-page-header :title="$title" :intro="$story->translate('excerpt')"
                   :trail="[[__('Projects'), localized_route('stories.index')]]" />

    <article class="mx-auto max-w-3xl px-4 py-12">
        <p class="text-xs font-bold uppercase text-accent">
            {{ $story->translate('category') }}
            @if ($story->published_at)
                · <time datetime="{{ $story->published_at->toDateString() }}">{{ \App\Support\Localization::digits($story->published_at->translatedFormat('j F Y')) }}</time>
            @endif
        </p>
        <x-photo :src="$story->image" :alt="$title" class="mt-4 h-64 sm:h-96" />
        @if (filled($body))
            <div class="mt-8 space-y-4 text-lg leading-relaxed text-gray-700">
                @foreach (preg_split('/\R{2,}/', trim($body)) as $paragraph)
                    <p>{{ $paragraph }}</p>
                @endforeach
            </div>
        @endif
    </article>

    @if ($moreStories->isNotEmpty())
        <x-section :heading="__('More Stories')" muted>
            <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($moreStories as $moreStory)
                    <x-story-card :story="$moreStory" />
                @endforeach
            </div>
        </x-section>
    @endif

    <x-donate-banner :message="__('Help us write the next success story.')" />
</x-layouts.app>

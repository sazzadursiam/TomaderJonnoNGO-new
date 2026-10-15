@props(['src' => null, 'alt' => '', 'icon' => null])

@php($url = media_url($src))

<div {{ $attributes->merge(['class' => 'relative overflow-hidden rounded-xl bg-gradient-to-br from-brand to-emerald-500']) }}>
    @if ($url)
        <img src="{{ $url }}" alt="{{ $alt }}" loading="lazy" decoding="async"
             class="absolute inset-0 h-full w-full object-cover transition-transform duration-300 hover:scale-105">
    @else
        <div class="absolute inset-0 flex items-center justify-center text-white/50" role="img" aria-label="{{ $alt }}">
            <x-ui.icon :name="$icon ?? 'heart'" class="size-12" />
        </div>
    @endif
</div>

@props(['src' => null, 'alt' => '', 'icon' => null])

@php($url = media_url($src))

<div {{ $attributes->merge(['class' => 'group/photo relative overflow-hidden rounded-xl bg-linear-to-br from-brand to-emerald-500']) }}>
    @if ($url)
        <img src="{{ $url }}" alt="{{ $alt }}" loading="lazy" decoding="async"
             class="absolute inset-0 h-full w-full object-cover transition-transform duration-700 ease-out group-hover:scale-105 group-hover/photo:scale-105">
    @else
        <div class="absolute inset-0 flex items-center justify-center text-white/50" role="img" aria-label="{{ $alt }}">
            <x-ui.icon :name="$icon ?? 'heart'" class="size-12 transition-transform duration-500 group-hover/photo:scale-110" />
        </div>
    @endif
</div>

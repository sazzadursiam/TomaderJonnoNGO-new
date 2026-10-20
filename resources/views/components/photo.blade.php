@props(['src' => null, 'alt' => '', 'icon' => null, 'lightbox' => false])

@php($url = media_url($src))

@if ($url && $lightbox)
    <a href="{{ $url }}" data-lightbox data-caption="{{ $alt }}"
       {{ $attributes->merge(['class' => 'group/photo relative block cursor-zoom-in overflow-hidden rounded-xl bg-linear-to-br from-brand to-emerald-500 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-brand']) }}>
        <img src="{{ $url }}" alt="{{ $alt }}" loading="lazy" decoding="async"
             class="absolute inset-0 h-full w-full object-cover transition-transform duration-700 ease-out group-hover/photo:scale-105">
        <span aria-hidden="true" class="absolute inset-0 flex items-center justify-center bg-brand/0 text-white opacity-0 transition duration-300 group-hover/photo:bg-brand/40 group-hover/photo:opacity-100">
            <x-ui.icon name="eye" class="size-8" />
        </span>
    </a>
@else
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
@endif

@props(['dark' => false])

@php use App\Support\Localization; @endphp

<div {{ $attributes->merge(['class' => 'flex overflow-hidden rounded font-semibold']) }} aria-label="{{ __('Language') }}">
    @foreach (Localization::LOCALES as $code => $label)
        @php($isCurrent = app()->getLocale() === $code)
        <a href="{{ Localization::switchUrl($code) }}" hreflang="{{ $code }}" lang="{{ $code }}"
           @if ($isCurrent) aria-current="true" @endif
           @class([
               'flex-1 px-3 py-1.5 text-center',
               'bg-white text-ink' => $isCurrent && $dark,
               'hover:bg-white/10' => ! $isCurrent && $dark,
               'bg-brand text-white' => $isCurrent && ! $dark,
               'hover:bg-brand-soft' => ! $isCurrent && ! $dark,
           ])>{{ $label }}</a>
    @endforeach
</div>

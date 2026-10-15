@props(['message'])

<div class="bg-brand text-white">
    <div class="mx-auto flex max-w-7xl flex-col items-center justify-between gap-5 px-4 py-10 text-center md:flex-row md:text-left">
        <h2 class="text-2xl font-extrabold">{{ $message }}</h2>
        <a href="{{ localized_route('donate') }}" class="btn-donate whitespace-nowrap">{{ __('Donate Now') }}</a>
    </div>
</div>

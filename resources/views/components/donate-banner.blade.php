@props(['message'])

<div class="animate-gradient bg-linear-to-r from-brand via-emerald-800 to-brand bg-size-[200%_200%] text-white">
    <div class="mx-auto flex max-w-7xl flex-col items-center justify-between gap-5 px-4 py-10 text-center md:flex-row md:text-left" data-reveal>
        <h2 class="flex items-center gap-3 text-2xl font-extrabold">
            <x-ui.icon name="heart" class="hidden size-7 animate-heartbeat text-red-300 md:block" />
            {{ $message }}
        </h2>
        <a href="{{ localized_route('donate') }}" class="btn-donate whitespace-nowrap">{{ __('Donate Now') }}</a>
    </div>
</div>

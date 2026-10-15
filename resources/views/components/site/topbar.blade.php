@php
    $phone = $site['phone'] ?? null;
    $email = $site['email'] ?? null;
@endphp

<div class="bg-ink text-xs text-gray-200">
    <div class="mx-auto flex h-10 max-w-7xl items-center justify-between px-4">
        <div class="flex items-center gap-4">
            @if ($phone)
                <a href="tel:{{ preg_replace('/[^0-9+]/', '', $phone) }}" class="inline-flex items-center gap-1.5 py-2 hover:text-white">
                    <x-ui.icon name="phone" class="size-3.5" /> {{ $phone }}
                </a>
            @endif
            @if ($email)
                <a href="mailto:{{ $email }}" class="inline-flex items-center gap-1.5 py-2 hover:text-white" aria-label="{{ __('Email') }}: {{ $email }}">
                    <x-ui.icon name="mail" class="size-3.5" /> <span class="hidden sm:inline">{{ $email }}</span>
                </a>
            @endif
        </div>
        <x-site.language-switch dark class="border border-gray-500" />
    </div>
</div>

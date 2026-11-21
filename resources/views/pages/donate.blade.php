@php
    $bankRows = collect([
        __('Bank') => $site['donation_bank_name'] ?? null,
        __('Account name') => $site['donation_account_name'] ?? null,
        __('Account number') => $site['donation_account_number'] ?? null,
        __('Branch') => $site['donation_branch'] ?? null,
        __('Routing number') => $site['donation_routing_number'] ?? null,
    ])->filter();

    $mobileRows = collect([
        'bKash' => $site['donation_bkash'] ?? null,
        'Nagad' => $site['donation_nagad'] ?? null,
    ])->filter();

    $mobileLogos = collect([
        'bKash' => ['images/payment/bkash.svg'],
        'Nagad' => ['images/payment/nagad.svg', 'images/payment/nagad.png'],
    ])->map(fn (array $paths) => collect($paths)->first(fn (string $path) => is_file(public_path($path))))->filter()->all();
    $bankLogo = str_contains(strtolower($site['donation_bank_name'] ?? ''), 'rupali') ? 'images/payment/rupali-bank.svg' : null;

    $note = translated($site['donation_note'] ?? null);
@endphp

<x-layouts.app :title="__('Donate')" :description="__('Support Tomader Jonno\'s work with a monthly or one-time gift.')">
    <x-page-header :title="__('Donate')" :intro="__('Your gift helps children learn, women earn, and communities grow. Thank you for standing with us.')" />

    <x-section>
        <div class="mx-auto max-w-3xl">
            <div class="space-y-6" data-reveal-stagger>
                <h2 class="text-2xl font-extrabold text-brand">{{ __('How to Donate') }}</h2>

                @if ($mobileRows->isNotEmpty())
                    <div class="rounded-xl border p-5 transition duration-300 hover:border-brand/30 hover:shadow-md">
                        <h3 class="flex items-center gap-2 font-bold text-brand"><x-ui.icon name="wallet" /> {{ __('Mobile Banking') }}</h3>
                        <dl class="mt-3 space-y-2">
                            @foreach ($mobileRows as $label => $value)
                                <div class="flex flex-wrap items-center justify-between gap-2 border-b pb-2 last:border-0">
                                    <dt class="text-gray-600">
                                        @isset($mobileLogos[$label])
                                            <img src="{{ asset($mobileLogos[$label]) }}" alt="{{ $label }}" class="h-9 w-auto" loading="lazy">
                                        @else
                                            {{ $label }}
                                        @endisset
                                    </dt>
                                    <dd class="font-semibold select-all">{{ $value }}</dd>
                                </div>
                            @endforeach
                        </dl>
                    </div>
                @endif

                @if ($bankRows->isNotEmpty())
                    <div class="rounded-xl border p-5 transition duration-300 hover:border-brand/30 hover:shadow-md">
                        <h3 class="flex flex-wrap items-center gap-2 font-bold text-brand">
                            <x-ui.icon name="bank" /> {{ __('Bank Transfer') }}
                            @if ($bankLogo)
                                <img src="{{ asset($bankLogo) }}" alt="{{ $site['donation_bank_name'] }}" class="ml-auto h-6 w-auto" loading="lazy">
                            @endif
                        </h3>
                        <dl class="mt-3 space-y-2">
                            @foreach ($bankRows as $label => $value)
                                <div class="flex flex-wrap justify-between gap-2 border-b pb-2 last:border-0">
                                    <dt class="text-gray-600">{{ $label }}</dt>
                                    <dd class="font-semibold select-all">{{ $value }}</dd>
                                </div>
                            @endforeach
                        </dl>
                    </div>
                @endif

                @if ($mobileRows->isEmpty() && $bankRows->isEmpty())
                    <p class="rounded-xl bg-surface p-5 text-gray-700">
                        {{ __('Donation details will be published soon. Please contact us to give today.') }}
                    </p>
                @endif

                @if (filled($note))
                    <p class="rounded-xl bg-brand-soft p-4 text-sm text-brand">{{ $note }}</p>
                @endif

                <p class="text-sm text-gray-600">
                    {{ __('After sending your gift, please let us know so we can thank you and share a receipt.') }}
                    <a href="{{ localized_route('contact') }}" class="font-semibold text-accent hover:underline">{{ __('Contact us') }}</a>
                </p>
            </div>
        </div>
    </x-section>
</x-layouts.app>

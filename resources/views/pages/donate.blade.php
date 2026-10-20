@php
    $amount = filter_var(request()->query('amount'), FILTER_VALIDATE_INT, ['options' => ['min_range' => config('donation.minimum')]]) ?: null;
    $frequency = request()->query('frequency') === 'once' ? 'once' : 'monthly';

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

    $note = translated($site['donation_note'] ?? null);
@endphp

<x-layouts.app :title="__('Donate')" :description="__('Support Tomader Jonno\'s work with a monthly or one-time gift.')">
    <x-page-header :title="__('Donate')" :intro="__('Your gift helps children learn, women earn, and communities grow. Thank you for standing with us.')" />

    <x-section>
        <div class="grid gap-10 lg:grid-cols-2">
            <div class="space-y-6" data-reveal-stagger>
                @if ($amount)
                    <div class="animate-fade-up rounded-xl border-2 border-accent bg-red-50 p-5" role="status">
                        <p class="font-semibold text-accent">
                            {{ $frequency === 'monthly'
                                ? __('You chose a monthly gift of ৳:amount.', ['amount' => local_number($amount)])
                                : __('You chose a one-time gift of ৳:amount.', ['amount' => local_number($amount)]) }}
                        </p>
                        <p class="mt-1 text-sm text-gray-700">{{ __('Please send your gift using one of the methods below.') }}</p>
                    </div>
                @endif

                <h2 class="text-2xl font-extrabold text-brand">{{ __('How to Donate') }}</h2>

                @if ($mobileRows->isNotEmpty())
                    <div class="rounded-xl border p-5 transition duration-300 hover:border-brand/30 hover:shadow-md">
                        <h3 class="flex items-center gap-2 font-bold text-brand"><x-ui.icon name="wallet" /> {{ __('Mobile Banking') }}</h3>
                        <dl class="mt-3 space-y-2">
                            @foreach ($mobileRows as $label => $value)
                                <div class="flex flex-wrap justify-between gap-2 border-b pb-2 last:border-0">
                                    <dt class="text-gray-600">{{ $label }}</dt>
                                    <dd class="font-semibold select-all">{{ $value }}</dd>
                                </div>
                            @endforeach
                        </dl>
                    </div>
                @endif

                @if ($bankRows->isNotEmpty())
                    <div class="rounded-xl border p-5 transition duration-300 hover:border-brand/30 hover:shadow-md">
                        <h3 class="flex items-center gap-2 font-bold text-brand"><x-ui.icon name="bank" /> {{ __('Bank Transfer') }}</h3>
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

            <div>
                <x-quick-give :tiers="$donationTiers" class="animate-slide-in-right lg:sticky lg:top-24" />
            </div>
        </div>
    </x-section>
</x-layouts.app>

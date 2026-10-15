@props(['tiers', 'minimum' => config('donation.minimum')])

@php
    $messages = [
        'empty' => __('Enter an amount to see your impact.'),
        'minimum' => __('The minimum gift is ৳:amount.'),
        'monthly' => __('Your monthly gift of ৳:amount :impact'),
        'once' => __('Your one-time gift of ৳:amount :impact'),
    ];
    $tabClass = 'py-3 aria-selected:bg-brand aria-selected:text-white';
    $amountClass = 'rounded-lg border-2 border-gray-200 py-3 text-sm font-semibold hover:border-accent aria-pressed:border-accent aria-pressed:bg-red-50 aria-pressed:text-accent';
@endphp

<div {{ $attributes->merge(['class' => 'rounded-2xl border border-gray-100 bg-white p-5 shadow-2xl sm:p-6']) }}
     data-quick-give data-minimum="{{ $minimum }}" data-messages='@json($messages)'>
    <h2 class="mb-4 text-xl font-bold text-brand">{{ __('Quick Give') }}</h2>

    <div class="grid grid-cols-2 overflow-hidden rounded-lg border border-brand text-sm font-semibold" role="tablist" aria-label="{{ __('Gift frequency') }}">
        <button type="button" role="tab" class="{{ $tabClass }}" data-frequency-tab="monthly" aria-selected="true">{{ __('Monthly') }}</button>
        <button type="button" role="tab" class="{{ $tabClass }}" data-frequency-tab="once" aria-selected="false">{{ __('Donate Once') }}</button>
    </div>

    <div class="mt-4 grid grid-cols-4 gap-2">
        @foreach ($tiers as $frequency => $frequencyTiers)
            @foreach ($frequencyTiers as $tier)
                <button type="button" class="{{ $amountClass }} @if ($frequency !== 'monthly') hidden @endif"
                        data-amount-button data-frequency="{{ $frequency }}" data-amount="{{ $tier['amount'] }}"
                        data-impact="{{ __($tier['impact']) }}" aria-pressed="false">
                    ৳{{ local_number($tier['amount']) }}
                </button>
            @endforeach
        @endforeach
        <button type="button" class="{{ $amountClass }}" data-custom-button aria-pressed="false">{{ __('Custom') }}</button>
    </div>

    <label class="sr-only" for="quick-give-amount">{{ __('Enter amount (৳)') }}</label>
    <input id="quick-give-amount" type="number" inputmode="numeric" min="{{ $minimum }}" step="1"
           placeholder="{{ __('Enter amount (৳)') }}" class="form-input mt-3 hidden focus:border-accent" data-custom-amount>

    <p class="mt-4 min-h-16 rounded-lg bg-brand-soft p-3 text-sm text-brand" data-impact-text aria-live="polite">
        {{ $messages['empty'] }}
    </p>

    <a href="{{ localized_route('donate') }}" data-donate-link data-base-url="{{ localized_route('donate') }}" class="btn-donate mt-4 w-full">
        {{ __('Donate Now') }}
    </a>
</div>

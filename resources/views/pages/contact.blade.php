@php($address = translated($site['address'] ?? null))

<x-layouts.app :title="__('Contact')" :description="__('We would love to hear from you.')">
    <x-page-header :title="__('Contact')" :intro="__('We would love to hear from you.')" />

    <x-section :heading="__('Get in Touch')">
        <div class="grid gap-10 lg:grid-cols-2">
            <div class="space-y-4" data-reveal-stagger>
                @if (filled($site['phone'] ?? null))
                    <div class="group flex items-start gap-4 rounded-xl border p-4 transition duration-300 hover:border-brand/30 hover:shadow-md">
                        <x-ui.icon name="phone" class="mt-1 size-6 text-brand transition-transform duration-300 group-hover:scale-110" />
                        <div>
                            <h3 class="font-bold text-brand">{{ __('Telephone') }}</h3>
                            <a href="tel:{{ preg_replace('/[^0-9+]/', '', $site['phone']) }}" class="hover:text-accent">{{ $site['phone'] }}</a>
                        </div>
                    </div>
                @endif
                @if (filled($site['email'] ?? null))
                    <div class="group flex items-start gap-4 rounded-xl border p-4 transition duration-300 hover:border-brand/30 hover:shadow-md">
                        <x-ui.icon name="mail" class="mt-1 size-6 text-brand transition-transform duration-300 group-hover:scale-110" />
                        <div>
                            <h3 class="font-bold text-brand">{{ __('Email') }}</h3>
                            <a href="mailto:{{ $site['email'] }}" class="hover:text-accent">{{ $site['email'] }}</a>
                        </div>
                    </div>
                @endif
                @if (filled($address))
                    <div class="group flex items-start gap-4 rounded-xl border p-4 transition duration-300 hover:border-brand/30 hover:shadow-md">
                        <x-ui.icon name="map-pin" class="mt-1 size-6 text-brand transition-transform duration-300 group-hover:scale-110" />
                        <div>
                            <h3 class="font-bold text-brand">{{ __('Office Location') }}</h3>
                            <p class="text-gray-600">{{ $address }}</p>
                        </div>
                    </div>
                @endif
            </div>

            <div class="rounded-2xl bg-surface p-6" data-reveal>
                <h3 class="mb-4 text-xl font-bold text-brand">{{ __('Send an Inquiry') }}</h3>

                @if (session('status'))
                    <p class="mb-4 animate-fade-up rounded-lg bg-brand-soft p-3 text-sm font-semibold text-brand" role="status">{{ session('status') }}</p>
                @endif

                <form method="POST" action="{{ localized_route('contact.store') }}" class="space-y-4" novalidate>
                    @csrf

                    <div class="hidden" aria-hidden="true">
                        <label for="website">Website</label>
                        <input id="website" name="website" type="text" tabindex="-1" autocomplete="off">
                    </div>

                    <div>
                        <label for="name" class="mb-1 block text-sm font-semibold">{{ __('Your name') }}</label>
                        <input id="name" name="name" type="text" value="{{ old('name') }}" required maxlength="120" autocomplete="name"
                               class="form-input @error('name') border-accent @enderror" @error('name') aria-invalid="true" aria-describedby="name-error" @enderror>
                        @error('name') <p id="name-error" class="mt-1 text-sm text-accent">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="contact" class="mb-1 block text-sm font-semibold">{{ __('Email or phone') }}</label>
                        <input id="contact" name="contact" type="text" value="{{ old('contact') }}" required maxlength="160" autocomplete="email"
                               class="form-input @error('contact') border-accent @enderror" @error('contact') aria-invalid="true" aria-describedby="contact-error" @enderror>
                        @error('contact') <p id="contact-error" class="mt-1 text-sm text-accent">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="message" class="mb-1 block text-sm font-semibold">{{ __('How can we help?') }}</label>
                        <textarea id="message" name="message" rows="5" required maxlength="3000"
                                  class="form-input @error('message') border-accent @enderror" @error('message') aria-invalid="true" aria-describedby="message-error" @enderror>{{ old('message') }}</textarea>
                        @error('message') <p id="message-error" class="mt-1 text-sm text-accent">{{ $message }}</p> @enderror
                    </div>

                    <button type="submit" class="btn-donate w-full">{{ __('Send Message') }}</button>
                </form>
            </div>
        </div>
    </x-section>
</x-layouts.app>

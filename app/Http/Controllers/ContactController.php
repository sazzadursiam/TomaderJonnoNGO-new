<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreContactMessageRequest;
use App\Models\ContactMessage;
use App\Support\Localization;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class ContactController extends Controller
{
    public function show(): View
    {
        return view('pages.contact');
    }

    public function store(StoreContactMessageRequest $request): RedirectResponse
    {
        if ($request->filled('website')) {
            return redirect(Localization::route('contact'))->with('status', __('Thank you! Your message has been sent.'));
        }

        ContactMessage::query()->create([
            ...$request->safe()->only(['name', 'contact', 'message']),
            'locale' => app()->getLocale(),
            'ip_address' => $request->ip(),
        ]);

        return redirect(Localization::route('contact'))->with('status', __('Thank you! Your message has been sent.'));
    }
}

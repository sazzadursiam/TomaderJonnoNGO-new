<?php

namespace App\Http\Middleware;

use App\Support\Localization;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Apply the locale that the matched route group is served in.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, string $locale = Localization::DEFAULT_LOCALE): Response
    {
        if (! Localization::isSupported($locale)) {
            $locale = Localization::DEFAULT_LOCALE;
        }

        app()->setLocale($locale);

        return $next($request);
    }
}

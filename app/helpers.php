<?php

use App\Support\Localization;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

if (! function_exists('localized_route')) {
    /**
     * URL for a named public route in the current (or given) locale.
     */
    function localized_route(string $name, mixed $parameters = [], ?string $locale = null): string
    {
        return Localization::route($name, $parameters, $locale);
    }
}

if (! function_exists('translated')) {
    /**
     * Pick the current locale's text from a {locale: text} array.
     *
     * @param  array<string, string|null>|string|null  $values
     */
    function translated(array|string|null $values): string
    {
        return Localization::pick($values);
    }
}

if (! function_exists('local_number')) {
    /**
     * Format a number for the current locale (Bangla digits on the Bangla site).
     */
    function local_number(int|float|string $value): string
    {
        return Localization::number($value);
    }
}

if (! function_exists('media_url')) {
    /**
     * Public URL for an uploaded file (public disk) or a bundled asset under public/.
     */
    function media_url(?string $path): ?string
    {
        if (blank($path)) {
            return null;
        }

        if (Str::startsWith($path, ['http://', 'https://', '/'])) {
            return $path;
        }

        if (Str::startsWith($path, 'images/')) {
            return asset($path);
        }

        return Storage::disk('public')->url($path);
    }
}

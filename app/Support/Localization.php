<?php

namespace App\Support;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

class Localization
{
    /**
     * Locales the public site is served in, keyed by code with their native label.
     *
     * @var array<string, string>
     */
    public const array LOCALES = [
        'en' => 'English',
        'bn' => 'বাংলা',
    ];

    /**
     * The locale served without a URL prefix.
     */
    public const string DEFAULT_LOCALE = 'en';

    private const array BANGLA_DIGITS = ['০', '১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯'];

    public static function isSupported(string $locale): bool
    {
        return array_key_exists($locale, self::LOCALES);
    }

    /**
     * Route names are registered once per locale; non-default locales are prefixed with "{locale}.".
     */
    public static function routeName(string $name, ?string $locale = null): string
    {
        $locale ??= app()->getLocale();

        return $locale === self::DEFAULT_LOCALE ? $name : "{$locale}.{$name}";
    }

    /**
     * Generate a URL for a named route in the given (or current) locale.
     */
    public static function route(string $name, mixed $parameters = [], ?string $locale = null): string
    {
        return route(self::routeName($name, $locale), $parameters);
    }

    /**
     * The URL of the current page in another locale, falling back to that locale's home page.
     */
    public static function switchUrl(string $locale): string
    {
        $currentRoute = Route::current();
        $currentName = $currentRoute?->getName();

        if ($currentName === null) {
            return self::route('home', locale: $locale);
        }

        foreach (array_keys(self::LOCALES) as $code) {
            $currentName = Str::after($currentName, "{$code}.");
        }

        $targetName = self::routeName($currentName, $locale);

        if (! Route::has($targetName)) {
            return self::route('home', locale: $locale);
        }

        return route($targetName, $currentRoute->parameters());
    }

    /**
     * Pick the current locale's value from a {locale: text} array, falling back to the default locale.
     *
     * @param  array<string, string|null>|string|null  $values
     */
    public static function pick(array|string|null $values, ?string $locale = null): string
    {
        if (! is_array($values)) {
            return (string) $values;
        }

        $locale ??= app()->getLocale();

        if (filled($values[$locale] ?? null)) {
            return $values[$locale];
        }

        return (string) ($values[self::DEFAULT_LOCALE] ?? collect($values)->filter()->first() ?? '');
    }

    /**
     * Format a number with thousands separators, using Bangla digits on the Bangla site.
     */
    public static function number(int|float|string $value, ?string $locale = null): string
    {
        $formatted = is_numeric($value) ? number_format((float) $value) : (string) $value;

        return self::digits($formatted, $locale);
    }

    /**
     * Convert ASCII digits in a string to Bangla digits on the Bangla site, leaving other characters untouched.
     */
    public static function digits(string $value, ?string $locale = null): string
    {
        if (($locale ?? app()->getLocale()) !== 'bn') {
            return $value;
        }

        return strtr($value, array_combine(array_map('strval', range(0, 9)), self::BANGLA_DIGITS));
    }
}

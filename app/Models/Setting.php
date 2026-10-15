<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

#[Fillable(['key', 'value'])]
class Setting extends Model
{
    private const string CACHE_KEY = 'site-settings';

    /**
     * Keys editable from the admin "Site Settings" page.
     *
     * @var list<string>
     */
    public const array KEYS = [
        'phone',
        'email',
        'address',
        'facebook_url',
        'youtube_url',
        'linkedin_url',
        'x_url',
        'donation_bank_name',
        'donation_account_name',
        'donation_account_number',
        'donation_branch',
        'donation_routing_number',
        'donation_bkash',
        'donation_nagad',
        'donation_note',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'value' => 'json',
        ];
    }

    protected static function booted(): void
    {
        static::saved(fn () => Cache::forget(self::CACHE_KEY));
        static::deleted(fn () => Cache::forget(self::CACHE_KEY));
    }

    /**
     * Get a setting value by key.
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        return self::allValues()[$key] ?? $default;
    }

    /**
     * All settings as a key => value map, cached until a setting changes.
     *
     * @return array<string, mixed>
     */
    public static function allValues(): array
    {
        return Cache::rememberForever(
            self::CACHE_KEY,
            fn (): array => self::query()->pluck('value', 'key')->all(),
        );
    }

    /**
     * Store several settings at once.
     *
     * @param  array<string, mixed>  $values
     */
    public static function store(array $values): void
    {
        foreach ($values as $key => $value) {
            self::query()->updateOrCreate(['key' => $key], ['value' => $value]);
        }
    }
}

<?php

namespace App\Models\Concerns;

use App\Support\Localization;

trait HasTranslations
{
    /**
     * Get a JSON {locale: text} attribute in the current locale, falling back to English.
     */
    public function translate(string $attribute, ?string $locale = null): string
    {
        return Localization::pick($this->getAttribute($attribute), $locale);
    }
}

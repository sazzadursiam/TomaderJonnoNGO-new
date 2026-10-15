<?php

namespace App\Filament\Support;

use App\Support\Localization;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Fieldset;

class BilingualField
{
    /**
     * A side-by-side English / Bangla input pair stored in one JSON {locale: text} column.
     *
     * When $required is true only English is required; an empty Bangla value falls back to English on the site.
     */
    public static function make(string $name, string $label, bool $multiline = false, bool $required = false, int $rows = 3): Fieldset
    {
        $fields = [];

        foreach (Localization::LOCALES as $locale => $localeLabel) {
            $field = $multiline
                ? Textarea::make("{$name}.{$locale}")->rows($rows)
                : TextInput::make("{$name}.{$locale}")->maxLength(255);

            $fields[] = $field
                ->label($localeLabel)
                ->required($required && $locale === Localization::DEFAULT_LOCALE);
        }

        return Fieldset::make($label)->schema($fields)->columns(2);
    }
}

<?php

namespace App\Filament\Resources\Stories\Schemas;

use App\Filament\Support\BilingualField;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class StoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Section::make('Publishing')
                    ->columns(3)
                    ->schema([
                        TextInput::make('slug')
                            ->label('URL slug')
                            ->helperText('Used in the page address, e.g. /projects/a-salon-of-her-own')
                            ->required()
                            ->maxLength(150)
                            ->alphaDash()
                            ->unique(ignoreRecord: true)
                            ->dehydrateStateUsing(fn (string $state): string => Str::slug($state)),
                        DateTimePicker::make('published_at')
                            ->label('Publish date')
                            ->default(now()),
                        Toggle::make('is_published')
                            ->label('Published')
                            ->default(true)
                            ->inline(false),
                    ]),

                Section::make('Story')
                    ->schema([
                        BilingualField::make('title', 'Title', required: true),
                        BilingualField::make('category', 'Category', required: true),
                        BilingualField::make('excerpt', 'Short summary', multiline: true, required: true, rows: 2),
                        BilingualField::make('body', 'Full story (leave a blank line between paragraphs)', multiline: true, rows: 10),
                        FileUpload::make('image')
                            ->image()
                            ->disk('public')
                            ->directory('stories')
                            ->maxSize(4096),
                    ]),
            ]);
    }
}

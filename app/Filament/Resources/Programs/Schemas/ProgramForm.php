<?php

namespace App\Filament\Resources\Programs\Schemas;

use App\Filament\Support\BilingualField;
use App\Models\Program;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class ProgramForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Section::make('Program')
                    ->columns(3)
                    ->schema([
                        TextInput::make('slug')
                            ->label('URL slug')
                            ->helperText('Used in the page address, e.g. /programs/education')
                            ->required()
                            ->maxLength(100)
                            ->alphaDash()
                            ->unique(ignoreRecord: true)
                            ->dehydrateStateUsing(fn (string $state): string => Str::slug($state)),
                        Select::make('icon')
                            ->options(Program::ICONS)
                            ->required(),
                        TextInput::make('sort_order')
                            ->numeric()
                            ->minValue(0)
                            ->default(0),
                        Toggle::make('is_published')
                            ->label('Published')
                            ->default(true),
                    ]),

                Section::make('Content')
                    ->schema([
                        BilingualField::make('title', 'Title', required: true),
                        BilingualField::make('summary', 'Card summary', required: true),
                        BilingualField::make('description', 'Page introduction', multiline: true, required: true),
                        BilingualField::make('overview', 'Program overview (optional)', multiline: true, rows: 4),
                        FileUpload::make('image')
                            ->image()
                            ->disk('public')
                            ->directory('programs')
                            ->maxSize(4096),
                    ]),

                Section::make('Focus areas')
                    ->description('Checklist shown on the program page.')
                    ->collapsible()
                    ->schema([
                        Repeater::make('focus_areas')
                            ->hiddenLabel()
                            ->schema([
                                BilingualField::make('title', 'Title', required: true),
                                BilingualField::make('text', 'Description', multiline: true, rows: 2),
                            ])
                            ->reorderable()
                            ->collapsible()
                            ->itemLabel(fn (array $state): ?string => $state['title']['en'] ?? null)
                            ->addActionLabel('Add focus area')
                            ->defaultItems(0),
                    ]),

                Section::make('Wings')
                    ->description('Sub-programs with their own photo gallery (used by Women\'s Empowerment).')
                    ->collapsible()
                    ->collapsed()
                    ->schema([
                        Repeater::make('wings')
                            ->hiddenLabel()
                            ->schema([
                                TextInput::make('label')
                                    ->placeholder('Wing A')
                                    ->maxLength(40),
                                BilingualField::make('title', 'Title', required: true),
                                BilingualField::make('text', 'Description', multiline: true, rows: 2),
                                FileUpload::make('images')
                                    ->image()
                                    ->multiple()
                                    ->reorderable()
                                    ->maxFiles(8)
                                    ->disk('public')
                                    ->directory('programs/wings')
                                    ->maxSize(4096),
                            ])
                            ->reorderable()
                            ->collapsible()
                            ->itemLabel(fn (array $state): ?string => $state['title']['en'] ?? null)
                            ->addActionLabel('Add wing')
                            ->defaultItems(0),
                    ]),
            ]);
    }
}

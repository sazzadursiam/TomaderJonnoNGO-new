<?php

namespace App\Filament\Resources\Stats;

use App\Filament\Resources\Stats\Pages\ManageStats;
use App\Filament\Support\BilingualField;
use App\Models\Stat;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class StatResource extends Resource
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedChartBar;

    protected static ?int $navigationSort = 4;

    protected static ?string $model = Stat::class;

    protected static ?string $navigationLabel = 'Impact numbers';

    protected static ?string $modelLabel = 'impact number';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                BilingualField::make('label', 'Label', required: true)->columnSpanFull(),
                TextInput::make('value')->numeric()->minValue(0)->required(),
                TextInput::make('suffix')->maxLength(8)->default('+'),
                TextInput::make('sort_order')->numeric()->minValue(0)->default(0),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('sort_order')
            ->reorderable('sort_order')
            ->columns([
                TextColumn::make('label')
                    ->state(fn (Stat $record): string => $record->translate('label', 'en'))
                    ->description(fn (Stat $record): string => $record->translate('label', 'bn')),
                TextColumn::make('value')
                    ->numeric()
                    ->suffix(fn (Stat $record): string => $record->suffix),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageStats::route('/'),
        ];
    }
}

<?php

namespace App\Filament\Resources\Programs\Tables;

use App\Models\Program;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;

class ProgramsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('sort_order')
            ->reorderable('sort_order')
            ->columns([
                ImageColumn::make('image')->disk('public')->square(),
                TextColumn::make('title')
                    ->label('Title')
                    ->state(fn (Program $record): string => $record->translate('title', 'en'))
                    ->description(fn (Program $record): string => $record->translate('title', 'bn')),
                TextColumn::make('slug')->color('gray'),
                ToggleColumn::make('is_published')->label('Published'),
                TextColumn::make('updated_at')->since()->sortable(),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}

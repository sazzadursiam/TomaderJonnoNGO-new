<?php

namespace App\Filament\Resources\Stories\Tables;

use App\Models\Story;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class StoriesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('published_at', 'desc')
            ->columns([
                ImageColumn::make('image')->disk('public')->square(),
                TextColumn::make('title')
                    ->label('Title')
                    ->state(fn (Story $record): string => $record->translate('title', 'en'))
                    ->description(fn (Story $record): string => $record->translate('category', 'en'))
                    ->wrap(),
                ToggleColumn::make('is_published')->label('Published'),
                TextColumn::make('published_at')->label('Publish date')->date()->sortable(),
            ])
            ->filters([
                TernaryFilter::make('is_published')->label('Published'),
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

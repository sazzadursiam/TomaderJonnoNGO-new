<?php

namespace App\Filament\Resources\TeamMembers;

use App\Filament\Resources\TeamMembers\Pages\ManageTeamMembers;
use App\Filament\Support\BilingualField;
use App\Models\TeamMember;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;

class TeamMemberResource extends Resource
{
    protected static ?string $model = TeamMember::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUserGroup;

    protected static ?int $navigationSort = 3;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                BilingualField::make('name', 'Name (leave empty until known — the role is shown instead)'),
                BilingualField::make('role', 'Role', required: true),
                BilingualField::make('bio', 'Short bio', multiline: true, rows: 2),
                FileUpload::make('photo')
                    ->image()
                    ->avatar()
                    ->imageEditor()
                    ->disk('public')
                    ->directory('team')
                    ->maxSize(2048),
                TextInput::make('sort_order')->numeric()->minValue(0)->default(0),
                Toggle::make('is_published')->label('Published')->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('sort_order')
            ->reorderable('sort_order')
            ->columns([
                ImageColumn::make('photo')->disk('public')->circular(),
                TextColumn::make('name')
                    ->state(fn (TeamMember $record): string => $record->translate('name', 'en') ?: '—')
                    ->description(fn (TeamMember $record): string => $record->translate('role', 'en')),
                ToggleColumn::make('is_published')->label('Published'),
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
            'index' => ManageTeamMembers::route('/'),
        ];
    }
}

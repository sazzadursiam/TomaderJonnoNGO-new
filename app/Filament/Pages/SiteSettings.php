<?php

namespace App\Filament\Pages;

use App\Filament\Support\BilingualField;
use App\Models\Setting;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\EmbeddedSchema;
use Filament\Schemas\Components\Form;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

/**
 * @property-read Schema $form
 */
class SiteSettings extends Page
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCog6Tooth;

    protected static ?int $navigationSort = 10;

    protected static ?string $title = 'Site Settings';

    /**
     * @var array<string, mixed>|null
     */
    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill(collect(Setting::KEYS)->mapWithKeys(fn (string $key) => [$key => Setting::get($key)])->all());
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->statePath('data')
            ->components([
                Section::make('Contact details')
                    ->description('Shown in the top bar, footer and Contact page.')
                    ->columns(2)
                    ->schema([
                        TextInput::make('phone')->tel()->maxLength(40),
                        TextInput::make('email')->email()->maxLength(120),
                        BilingualField::make('address', 'Office address', multiline: true, rows: 2)->columnSpanFull(),
                    ]),

                Section::make('Social links')
                    ->description('Leave empty to hide an icon in the footer.')
                    ->columns(2)
                    ->collapsible()
                    ->schema([
                        TextInput::make('facebook_url')->label('Facebook')->url()->maxLength(255),
                        TextInput::make('youtube_url')->label('YouTube')->url()->maxLength(255),
                        TextInput::make('linkedin_url')->label('LinkedIn')->url()->maxLength(255),
                        TextInput::make('x_url')->label('X (Twitter)')->url()->maxLength(255),
                    ]),

                Section::make('Donation details')
                    ->description('Shown on the Donate page. Leave a field empty to hide it.')
                    ->columns(2)
                    ->collapsible()
                    ->schema([
                        TextInput::make('donation_bkash')->label('bKash number')->maxLength(40),
                        TextInput::make('donation_nagad')->label('Nagad number')->maxLength(40),
                        TextInput::make('donation_bank_name')->label('Bank name')->maxLength(120),
                        TextInput::make('donation_account_name')->label('Account name')->maxLength(120),
                        TextInput::make('donation_account_number')->label('Account number')->maxLength(60),
                        TextInput::make('donation_branch')->label('Branch')->maxLength(120),
                        TextInput::make('donation_routing_number')->label('Routing number')->maxLength(40),
                        BilingualField::make('donation_note', 'Note for donors', multiline: true, rows: 2)->columnSpanFull(),
                    ]),
            ]);
    }

    public function content(Schema $schema): Schema
    {
        return $schema
            ->components([
                Form::make([EmbeddedSchema::make('form')])
                    ->id('form')
                    ->livewireSubmitHandler('save')
                    ->footer([
                        Actions::make([
                            Action::make('save')
                                ->label('Save settings')
                                ->submit('save')
                                ->keyBindings(['mod+s']),
                        ]),
                    ]),
            ]);
    }

    public function save(): void
    {
        Setting::store($this->form->getState());

        Notification::make()
            ->success()
            ->title('Settings saved')
            ->send();
    }
}

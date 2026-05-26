<?php

namespace App\Filament\Pages;

use App\Models\Appointment;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Actions\Action;
use Filament\Forms\Components\DateTimePicker;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Schemas\Schema;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use BackedEnum;

class Gestionar extends Page implements HasForms, HasActions
{
    use InteractsWithForms;
    use InteractsWithActions;

    protected string $view = 'filament.pages.gestionar';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedChatBubbleLeftEllipsis;

    /** @var array Holds the form data linked via statePath('data') */
    public ?array $data = [];
    
    /** @var Appointment|null Stores the retrieved appointment model for editing */
    public ?Appointment $appointment = null;

    /**
     * This method runs once when the page is first loaded.
     * It initializes the form state.
     */
    public function mount(): void
    {
        $this->form->fill();
    }

    /**
     * Defines the form schema for the page.
     * It contains a search section and a details section that is only visible 
     * once an appointment is found.
     */
    public function form(Schema $form): Schema
    {
        return $form
            ->components([
                Section::make('Búsqueda de Cita')
                    ->description('Ingrese el documento del paciente para gestionar su información.')
                    ->schema([
                        TextInput::make('search_document')
                            ->label('Documento de Identidad')
                            ->placeholder('Ej: 12345678')
                            ->required()
                            ->suffixAction(
                                Action::make('search')
                                    ->icon('heroicon-o-magnifying-glass')
                                    ->color('primary')
                                    ->action(fn () => $this->handleSearch()),
                            ),
                    ]),

                Section::make('Detalles de la Cita')
                    ->schema([
                        TextInput::make('name')
                            ->label('Nombre Completo')
                            ->required(),
                        TextInput::make('email')
                            ->label('Correo Electrónico')
                            ->email()
                            ->required(),
                        TextInput::make('document')
                            ->label('Documento')
                            ->required(),
                        DateTimePicker::make('appoiment_date')
                            ->label('Fecha y Hora')
                            ->required(),
                        TextInput::make('call_disposition')
                            ->label('Disposición de la llamada'),
                    ])
                    ->visible(fn () => $this->appointment !== null),
            ])
            ->statePath('data');
    }

    /**
     * Searches for an appointment based on the document number provided in the search field.
     * If found, it populates the form with the database values.
     */
    public function handleSearch(): void
    {
        // We use getRawState() to get the document number without triggering 
        // validation on the hidden "Details" fields.
        $searchQuery = $this->data['search_document'] ?? null;

        if (! $searchQuery) return;

        // Query the database for the appointment
        $this->appointment = Appointment::where('document', $searchQuery)->first();

        if ($this->appointment) {
            // Fill the $data array and the form fields with the found model's data
            $this->form->fill([ 
                'search_document' => $searchQuery,
                'name' => $this->appointment->name,
                'email' => $this->appointment->email,
                'document' => $this->appointment->document,
                'appoiment_date' => $this->appointment->appoiment_date,
                'call_disposition' => $this->appointment->call_disposition,
            ]);
        } else {
            Notification::make()
                ->title('No se encontró ninguna cita con ese documento.')
                ->danger()
                ->send();
        }
    }

    /**
     * Validates and saves the updated information back to the database.
     */
    public function save(): void
    {
        // Validates all required fields in the form
        $state = $this->form->getState();
        
        // Updates the retrieved appointment model with the new state
        $this->appointment->update($state);

        // Displays a success toast notification to the user
        Notification::make()
            ->title('Cita actualizada correctamente.')
            ->success()
            ->send();
    }
}

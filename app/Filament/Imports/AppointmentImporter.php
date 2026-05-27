<?php

namespace App\Filament\Imports;

use App\Models\Appointment;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Illuminate\Support\Number;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Log;


class AppointmentImporter extends Importer
{
    protected static ?string $model = Appointment::class;

    // public static function shouldQueue(): bool
    // {
    //     return false;
    // }

    public static function getColumns(): array
    {
        return [
            // ImportColumn::make('name')
            //     // ->requiredMapping()
            //     ->rules(['required', 'max:255']),
            // ImportColumn::make('email')
            //     // ->requiredMapping()
            //     ->rules(['required', 'email', 'max:255']),
            // ImportColumn::make('document')
            //     // ->requiredMapping()
            //     ->rules(['required', 'max:255']),
            // ImportColumn::make('appoiment_date')
            //     // ->requiredMapping()
            // // ->rules(['required', 'datetime'])
            // ,
            // ImportColumn::make('call_disposition')
            //     // ->requiredMapping()
            //     ->rules(['required', 'max:255']),

            ImportColumn::make('name')
                ->exampleHeader('Nombre')
                ->label('Nombre'),
            ImportColumn::make('email')
                ->exampleHeader('Email')
                // ->rules(['email'])
                ->label('Email'),
            ImportColumn::make('document')
                ->label('Documento'),
            ImportColumn::make('appoiment_date')
                ->label('Fecha de Cita'),
            ImportColumn::make('call_disposition')
                ->label('Tipificación'),
        ];
    }

    public function resolveRecord(): Appointment
    {
        return new Appointment();
    }

    public function fillRecord(): void
    {
        //  dd($this->data);
         Log::info('Processing row', $this->data);
        $this->record->name = $this->data['name'];
        $this->record->email = $this->data['email'];
        $this->record->document = $this->data['document'];
        $this->record->appoiment_date = $this->data['appoiment_date'];
        $this->record->call_disposition = $this->data['call_disposition'];
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $successfulRowsLabel = $import->successful_rows === 1 ? 'fila' : 'filas';
        $body = 'La importación de citas se completó y se importaron ' . Number::format($import->successful_rows) . " {$successfulRowsLabel}.";

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $failedRowsLabel = $failedRowsCount === 1 ? 'fila' : 'filas';
            $body .= ' No se pudieron importar ' . Number::format($failedRowsCount) . " {$failedRowsLabel}.";
        }

        return $body;
    }

    public function onFailure(\Throwable $exception): void
    {
        dd($exception->getMessage());
    }

    public static function getExampleRows(): array
    {
        return [
            [
                'Nombre' => 'Juan Pérez',
                'Email' => 'juan@example.com',
                'Documento' => '123456',
                'Fecha de Cita' => '2026-05-03 14:00:00',
                'Tipificación' => 'Venta',
            ],
        ];
    }

    public static function modifyCompletedNotification(Notification $notification, Import $import): Notification
    {
        if ($import->failed_rows_count > 0) {
            return $notification
                ->persistent()
                ->warning()
                ->title('Importación completada con errores');
        }

        return $notification
            ->persistent()
            ->success()
            ->title('Importación exitosa');
    }

    // Size of each chunk of records to be processed.

    public static function getChunkSize(): int
    {
        return 100;
    }

    // The name of the queue to which the import job should be dispatched. if you need to specify a custom queue for the import job, you can override the getJobQueue method in your importer class:

    // public function getJobQueue(): ?string
    // {
    //     return 'imports';
    // }
    // // The connection on which the import job should be processed.
    // public function getJobConnection(): ?string
    // {
    //     return 'database';
    // }
    // // The name of the batch to which the import job should be added when using chunking.
    // public function getJobBatchName(): ?string
    // {
    //     return 'Appointments Import';
    // }

}

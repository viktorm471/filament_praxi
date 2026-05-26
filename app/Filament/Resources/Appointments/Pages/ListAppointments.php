<?php

namespace App\Filament\Resources\Appointments\Pages;

use App\Filament\Resources\Appointments\AppointmentResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions\ImportAction;
use Filament\Actions\Action;
use App\Filament\Imports\AppointmentImporter;

class ListAppointments extends ListRecords
{
    protected static string $resource = AppointmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
            ImportAction::make()
                ->importer(AppointmentImporter::class),
            // Action::make('downloadTemplate')
            //     ->label('Descargar plantilla')
            //     ->action(fn () => response()->streamDownload(function () {
            //         echo "name,email,document,appoiment_date,call_disposition\n";
            //         echo "Juan,juan@email.com,123,2026-05-03 14:00:00,Venta\n";
            //     }, 'plantilla.csv')),
        ];
    }

}

<?php

namespace App\Filament\Resources\Pacients\Pages;

use App\Filament\Resources\Pacients\PacientResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManagePacients extends ManageRecords
{
    protected static string $resource = PacientResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}

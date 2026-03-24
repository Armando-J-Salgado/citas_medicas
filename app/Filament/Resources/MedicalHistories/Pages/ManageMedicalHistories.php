<?php

namespace App\Filament\Resources\MedicalHistories\Pages;

use App\Filament\Resources\MedicalHistories\MedicalHistoryResource;
use App\Models\Pacient;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;
use Illuminate\Validation\ValidationException;
use function PHPUnit\Framework\isNan;

class ManageMedicalHistories extends ManageRecords
{
    protected static string $resource = MedicalHistoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->mutateFormDataUsing(function (array $data): array {
                $this->validateMedicalHistory($data);
                return $data;
            }),
        ];
    }

    private function validateMedicalHistory(array $data): void
    {
        $pacientId = $data["pacient_id"] ?? null;

        $pacient = Pacient::find($pacientId);

        if ($pacient && $pacient->MedicalHistory) {
            throw ValidationException::withMessages([
                'user' => 'The pacient does not have available schedules at this time or already has an appointment.',
            ]);
        }
    }
}

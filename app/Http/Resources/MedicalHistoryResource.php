<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MedicalHistoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'pacient_id' => $this->pacient_id,
            'weight' => $this->weight . ' lb',
            'height' => $this->height . ' m',
            'chronic_diseases' => $this->chronic_diseases,
            'allergies' => $this->allergies,
            'date_of_birth' => $this->date_of_birth,
            'medications' => $this->medications
        ];
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Pacient;

class MedicalHistory extends Model
{
    /** @use HasFactory<\Database\Factories\MedicalHistoryFactory> */
    use HasFactory;

    protected $fillable = [
        'weight',
        'height',
        'chronic_diseases',
        'allergies',
        'date_of_birth',
        'medications',
        'created_at',
        'updated_at',
    ];

    public function pacient() : BelongsTo
    {
        return $this->belongsTo(Pacient::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use App\Models\MedicalHistory;
use App\Models\Appointment;

class Pacient extends Model
{
    /** @use HasFactory<\Database\Factories\PacientFactory> */
    use HasFactory;

    protected $fillable = [
        "name",
        "lastname",
        "dui",
        "phone_number",
        "gender",
        "updated_at",
        "created_at",
    ];

    public function medicalHistory(): HasOne
    {
        return $this->hasOne(MedicalHistory::class);
    }

    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class);
    }
}

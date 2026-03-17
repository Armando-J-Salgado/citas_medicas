<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;

class Schedule extends Model
{
    /** @use HasFactory<\Database\Factories\ScheduleFactory> */
    use HasFactory;

    protected $fillable = [
        "day_of_week",
        "start_at",
        "end_at",
        "user_id",
        "created_at",
        "updated_at",
    ];

    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

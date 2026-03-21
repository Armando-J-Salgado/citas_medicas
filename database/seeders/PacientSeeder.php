<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pacient;
use App\Models\MedicalHistory;

class PacientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Pacient::factory(50)->has(MedicalHistory::factory()->count(1), 'medicalHistory')->create();
    }
}

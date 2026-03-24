<?php

namespace Database\Seeders;

use App\Models\Pacient;
use Illuminate\Database\Seeder;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use App\Models\Schedule;
use App\Models\Appointment;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->create([
            "name"=>"Admin",
            "email"=> "admin@mail.com",
            "password"=>Hash::make("Software@26"),
        ])->assignRole("administrador");

        User::factory()->create([
            "name"=>"Stephen Strange",
            "email"=> "doctor@mail.com",
            "password"=>Hash::make("Software@26"),
        ])->assignRole("medico");

        User::factory()->create([
            "name"=> "Johny Storm",
            "email"=>"asistente@email.com",
            "password"=>Hash::make("Software@26"),
        ])->assignRole("asistente");

        // Usuarios asistentes
        User::factory(10)->asistente()->create()->each(function ($user) {
            $user->assignRole("asistente");
        });

        //Este solo aplicará para los perfiles de rol doctor
        User::factory(20)->doctor()->create()->each(function ($user) {

            $user->assignRole("medico");

            $days = [0,1,2,3,4,5,6,];
            shuffle($days);
            $selectedDays = array_slice($days,0,random_int(1, 5));

            foreach ($selectedDays as $day) {
                $allBlocks = range(7, 16);
                shuffle($allBlocks);
                $selectedBlocks = array_slice($allBlocks, 0, random_int(4, 6));

                foreach ($selectedBlocks as $start) {
                    //Generar slots
                    Schedule::create([
                        "user_id"=> $user->id,
                        "day_of_week" => $day,
                        "start_at"=> $start . ":00:00",
                        "end_at"=>($start + 1) . ":00:00",
                    ]);

                    //Generar appointments

                    //Información del paciente
                    $pacientId = random_int(1, 50);
                    $pacient = Pacient::firstWhere('id', $pacientId);
                    $birthDate = $pacient->medicalHistory()->first()->date_of_birth;

                    //Fecha aleatoria posterior al nacimiento
                    $randomDate = fake()->dateTimeBetween($birthDate, now());
                    $carbon = Carbon::parse($randomDate);
                    //Determina cuántas fechas avanzar para conseguir un día coincidente (Lunes con Lunes)
                    $diff = ($day - $carbon->dayOfWeek + 7) % 7;
                    $carbon->addDays($diff);
                    //Evalúa si la fecha no sobrepasa la fecha actual
                    if ($carbon->isFuture()) {
                        //Regresa 7 días atrás para mantener el día (Lunes regresa a Lunes)
                        $carbon->subDays(7);
                    }
                    //Verificación final: Sigue siendo mayor al nacimiento
                    $birth = Carbon::parse($birthDate);
                    if ($carbon->lessThanOrEqualTo($birth)) {
                        //No se crea un appointment porque la fecha no es posible
                        continue;
                    }
                    //Se crea la fecha
                    $date = $carbon->format('Y-m-d');

                    //Cita médica creada
                    Appointment::create([
                        "start_at"=> $date . ' ' . $start . ":00:00",
                        "end_at" => $date . ' ' . $start . ":30:00",
                        "user_id"=>$user->id,
                        "pacient_id"=> $pacientId,
                    ]);
                }
            }
        });
    }
}

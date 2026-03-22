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


        // Usuarios asistentes
        User::factory(5)->asistente()->create()->each(function ($user) {
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
                    $pacientId = random_int(1, 50);
                    $pacient = Pacient::firstWhere('id', $pacientId);
                    $date = fake()->dateTimeBetween($pacient->medicalHistory->date_of_birth, now());
                    $date = $date->format('Y-m-d');
                    Appointment::create([
                        "start_at"=> $date . ' ' . $start . ":00:00",
                        "end_at" => $date . ' ' . $start . ":30:00",
                        "user_id"=>$user->id,
                        "pacient_id"=> $pacientId,
                    ]);
                }
            }
        });
        //TO DO: A quién le toquen roles y policies, LA FUNCIÓN DE ARRIBA SE MODIFICA SEGUN LAS SIGUIENTES INDICACIONES:
        //usar User::factory([numero_copias])->[funcion_role]()->create() para crear usuarios con roles especificos
        //ej. User::factory(15)->doctor()->create();
        //ej. User::factory(15)->asistente()->create();
    }
}

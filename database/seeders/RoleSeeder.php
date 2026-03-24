<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = Role::firstOrCreate(['name' => 'administrador', 'guard_name' => 'web']);
        $medico = Role::firstOrCreate(['name' => 'medico', 'guard_name' => 'web']);
        $asistente = Role::firstOrCreate(['name' => 'asistente', 'guard_name' => 'web']);

        $admin->syncPermissions([
            'users.view', 'users.create', 'users.update', 'users.delete',
            'pacients.view', 'pacients.create', 'pacients.update', 'pacients.delete',
            'medical_histories.view', 'medical_histories.create', 'medical_histories.update', 'medical_histories.delete',
            'schedules.view', 'schedules.create', 'schedules.update', 'schedules.delete',
            'appointments.view', 'appointments.create', 'appointments.update', 'appointments.delete',
        ]);

        $medico->syncPermissions([
            'pacients.view',
            'medical_histories.view', 'medical_histories.create', 'medical_histories.update',
            'schedules.view', 
            'appointments.view', 'appointments.create', 'appointments.update',
        ]);

        $asistente->syncPermissions([
            'pacients.view', 'pacients.create', 'pacients.update',
            'medical_histories.view', 'medical_histories.create',
            'schedules.view', 'schedules.create', 'schedules.update',
            'appointments.view', 'appointments.create', 'appointments.update',
        ]);
    }
}

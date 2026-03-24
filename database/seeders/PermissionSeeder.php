<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $permissions = [
            'users.view',
            'users.create',
            'users.update',
            'users.delete',

            'pacients.view',
            'pacients.create',
            'pacients.update',
            'pacients.delete',

            'medical_histories.view',
            'medical_histories.create',
            'medical_histories.update',
            'medical_histories.delete',

            'schedules.view',
            'schedules.create',
            'schedules.update',
            'schedules.delete',

            'appointments.view',
            'appointments.create',
            'appointments.update',
            'appointments.delete',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }
    }
}

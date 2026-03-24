<?php

use App\Models\Appointment;
use App\Models\MedicalHistory;
use App\Models\Pacient;
use App\Models\Schedule;
use App\Models\User;
use Database\Seeders\PermissionSeeder;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    $this->seed([
        PermissionSeeder::class,
        RoleSeeder::class,
    ]);
});

function autenticar(User $usuario): void
{
    Sanctum::actingAs($usuario);
}

function crearUsuarioConRol(string $rol): User
{
    $usuario = User::factory()->create();
    $usuario->assignRole($rol);

    return $usuario;
}

function crearDatosBaseParaCitas(): array
{
    $doctor = User::factory()->create();
    $doctor->assignRole('medico');

    $pacient = Pacient::factory()->create();

    Schedule::query()->create([
        'user_id' => $doctor->id,
        'day_of_week' => 1,
        'start_at' => '09:00:00',
        'end_at' => '12:00:00',
    ]);

    return [$doctor, $pacient];
}

function crearHistorialMedico(Pacient $pacient): MedicalHistory
{
    return MedicalHistory::query()->create([
        'pacient_id' => $pacient->id,
        'weight' => 70,
        'height' => 1.70,
        'chronic_diseases' => 'ninguna',
        'allergies' => 'ninguna',
        'date_of_birth' => '2000-01-01',
        'medications' => 'ninguna',
    ]);
}

// 1. No permite listar citas sin autenticacion
test("it can't list appointments without authentication", function () {
    $this->getJson('/api/v1/appointments')
        ->assertUnauthorized();
});

// 2. No permite ver perfil sin autenticacion
test("it can't get profile without authentication", function () {
    $this->getJson('/api/v1/auth/profile')
        ->assertUnauthorized();
});

// 3. Puede hacer login con credenciales validas
test('it can login with valid credentials', function () {
    $passwordPlano = 'Password1234';

    User::factory()->create([
        'email' => 'login@test.com',
        'password' => Hash::make($passwordPlano),
    ]);

    $this->postJson('/api/v1/auth/login', [
        'email' => 'login@test.com',
        'password' => $passwordPlano,
    ])->assertOk()
        ->assertJsonStructure(['token', 'user']);
});

// 4. Rechaza login con credenciales invalidas
test("it can't login with invalid credentials", function () {
    User::factory()->create([
        'email' => 'login2@test.com',
        'password' => Hash::make('Password1234'),
    ]);

    $this->postJson('/api/v1/auth/login', [
        'email' => 'login2@test.com',
        'password' => 'PasswordIncorrecto',
    ])->assertStatus(422);
});

// 5. Permite ver perfil con autenticacion
test('it can get profile with authentication', function () {
    $usuario = User::factory()->create();
    autenticar($usuario);

    $this->getJson('/api/v1/auth/profile')
        ->assertOk();
});

// 6. Permite listar citas con autenticacion
test('it can list appointments with authentication', function () {
    $usuario = User::factory()->create();
    autenticar($usuario);

    $this->getJson('/api/v1/appointments')
        ->assertOk();
});

// 7. Crea una cita cuando el doctor tiene horario disponible
test('it can create an appointment when doctor has availability', function () {
    $usuario = User::factory()->create();
    autenticar($usuario);

    [$doctor, $pacient] = crearDatosBaseParaCitas();

    $inicio = Carbon::create(2026, 3, 23, 10, 0, 0)->format('Y-m-d H:i:s');

    $this->postJson('/api/v1/appointments', [
        'start_at' => $inicio,
        'user_id' => $doctor->id,
        'pacient_id' => $pacient->id,
    ])->assertCreated();

    $this->assertDatabaseHas('appointments', [
        'user_id' => $doctor->id,
        'pacient_id' => $pacient->id,
        'start_at' => $inicio,
        'end_at' => Carbon::parse($inicio)->addMinutes(30)->format('Y-m-d H:i:s'),
    ]);
});

// 8. Rechaza crear cita fuera del horario del doctor
test("it can't create an appointment outside doctor's schedule", function () {
    $usuario = User::factory()->create();
    autenticar($usuario);

    [$doctor, $pacient] = crearDatosBaseParaCitas();

    $this->postJson('/api/v1/appointments', [
        'start_at' => Carbon::create(2026, 3, 23, 13, 0, 0)->format('Y-m-d H:i:s'),
        'user_id' => $doctor->id,
        'pacient_id' => $pacient->id,
    ])->assertUnprocessable()
        ->assertJsonValidationErrors(['start_at']);
});

// 9. Rechaza crear cita cuando hay conflicto de hora
test("it can't create an appointment when there is a time conflict", function () {
    $usuario = User::factory()->create();
    autenticar($usuario);

    [$doctor, $pacient] = crearDatosBaseParaCitas();

    Appointment::query()->create([
        'start_at' => '2026-03-23 10:00:00',
        'end_at' => '2026-03-23 10:30:00',
        'user_id' => $doctor->id,
        'pacient_id' => $pacient->id,
    ]);

    $this->postJson('/api/v1/appointments', [
        'start_at' => '2026-03-23 10:15:00',
        'user_id' => $doctor->id,
        'pacient_id' => $pacient->id,
    ])->assertUnprocessable()
        ->assertJsonValidationErrors(['start_at']);
});

// 10. No permite crear cita con informacion invalida
test("it can't create an appointment with invalid information", function () {
    $usuario = User::factory()->create();
    autenticar($usuario);

    [$doctor, $pacient] = crearDatosBaseParaCitas();

    $this->postJson('/api/v1/appointments', [
        'start_at' => 'fecha-invalida',
        'user_id' => $doctor->id,
        'pacient_id' => $pacient->id,
    ])->assertUnprocessable()
        ->assertJsonValidationErrors(['start_at']);
});

// 11. Actualiza una cita y recalcula el fin automaticamente
test('it can update an appointment and recalculate end time', function () {
    $usuario = User::factory()->create();
    autenticar($usuario);

    [$doctor, $pacient] = crearDatosBaseParaCitas();

    $appointment = Appointment::query()->create([
        'start_at' => '2026-03-23 10:00:00',
        'end_at' => '2026-03-23 10:30:00',
        'user_id' => $doctor->id,
        'pacient_id' => $pacient->id,
    ]);

    $this->putJson("/api/v1/appointments/{$appointment->id}", [
        'start_at' => '2026-03-23 11:00:00',
    ])->assertOk();

    $this->assertDatabaseHas('appointments', [
        'id' => $appointment->id,
        'start_at' => '2026-03-23 11:00:00',
        'end_at' => '2026-03-23 11:30:00',
    ]);
});

// 12. Elimina una cita existente
test('it can delete an existing appointment', function () {
    $usuario = User::factory()->create();
    autenticar($usuario);

    [$doctor, $pacient] = crearDatosBaseParaCitas();

    $appointment = Appointment::query()->create([
        'start_at' => '2026-03-23 10:00:00',
        'end_at' => '2026-03-23 10:30:00',
        'user_id' => $doctor->id,
        'pacient_id' => $pacient->id,
    ]);

    $this->deleteJson("/api/v1/appointments/{$appointment->id}")
        ->assertNoContent();

    $this->assertDatabaseMissing('appointments', [
        'id' => $appointment->id,
    ]);
});

// 13. Admin: puede crear paciente
test('it can create pacient as admin', function () {
    $admin = crearUsuarioConRol('administrador');
    autenticar($admin);

    $this->postJson('/api/v1/pacient', [
        'name' => 'Maria',
        'lastname' => 'Lopez',
        'dui' => '12345678-9',
        'phone_number' => '7123-4567',
        'gender' => 'female',
    ])->assertCreated();
});

// 14. Admin: puede actualizar paciente
test('it can update pacient as admin', function () {
    $admin = crearUsuarioConRol('administrador');
    autenticar($admin);

    $pacient = Pacient::factory()->create();

    $this->patchJson("/api/v1/pacient/{$pacient->id}", [
        'phone_number' => '7555-1234',
    ])->assertOk();

    $this->assertDatabaseHas('pacients', [
        'id' => $pacient->id,
        'phone_number' => '7555-1234',
    ]);
});

// 15. Admin: no puede crear paciente si la informacion es invalida
test("it can't create pacient with invalid information as admin", function () {
    $admin = crearUsuarioConRol('administrador');
    autenticar($admin);

    $this->postJson('/api/v1/pacient', [
        'name' => 'Carlos',
        'lastname' => '',
        'dui' => '32345678-9',
        'phone_number' => 'formato-invalido',
        'gender' => 'female',
    ])->assertUnprocessable()
        ->assertJsonValidationErrors(['lastname', 'phone_number']);
});

// 16. Admin: puede crear expediente medico
test('it can create medical history as admin', function () {
    $admin = crearUsuarioConRol('administrador');
    autenticar($admin);

    $pacient = Pacient::factory()->create();

    $this->postJson('/api/v1/medical-history', [
        'pacient_id' => $pacient->id,
        'weight' => 70.5,
        'height' => 1.72,
        'chronic_diseases' => 'none',
        'allergies' => 'none',
        'date_of_birth' => '2000-01-01',
        'medications' => 'none',
    ])->assertCreated();
});

// 17. Admin: no puede crear expediente medico con informacion invalida
test("it can't create medical history with invalid information as admin", function () {
    $admin = crearUsuarioConRol('administrador');
    autenticar($admin);

    $pacient = Pacient::factory()->create();

    $this->postJson('/api/v1/medical-history', [
        'pacient_id' => $pacient->id,
        'weight' => -1,
        'height' => 1.72,
        'chronic_diseases' => 'none',
        'allergies' => 'none',
        'date_of_birth' => '2000-01-01',
        'medications' => 'none',
    ])->assertUnprocessable()
        ->assertJsonValidationErrors(['weight']);
});

// 18. Admin: puede crear usuario
test('it can create user as admin via endpoint', function () {
    $admin = crearUsuarioConRol('administrador');
    autenticar($admin);

    $this->postJson('/api/v1/users', [
        'name' => 'Nuevo Admin User',
        'email' => 'admin.user.endpoint@test.com',
        'password' => 'Password1234',
    ])->assertCreated();

    $this->assertDatabaseHas('users', [
        'email' => 'admin.user.endpoint@test.com',
    ]);
});

// 19. Admin: no puede crear usuario con informacion invalida
test("it can't create user with invalid information as admin", function () {
    $admin = crearUsuarioConRol('administrador');
    autenticar($admin);

    $this->postJson('/api/v1/users', [
        'name' => '',
        'email' => 'correo-invalido',
        'password' => '123',
    ])->assertUnprocessable()
        ->assertJsonValidationErrors(['name', 'email', 'password']);
});

// 20. Admin: puede ver un usuario
test('it can view a user as admin via endpoint', function () {
    $admin = crearUsuarioConRol('administrador');
    autenticar($admin);

    $targetUser = User::factory()->create();

    $this->getJson("/api/v1/users/{$targetUser->id}")
        ->assertOk();
});

// 21. Admin: puede actualizar un usuario
test('it can update a user as admin via endpoint', function () {
    $admin = crearUsuarioConRol('administrador');
    autenticar($admin);

    $targetUser = User::factory()->create();

    $this->putJson("/api/v1/users/{$targetUser->id}", [
        'name' => 'Usuario Actualizado',
    ])->assertOk();

    $this->assertDatabaseHas('users', [
        'id' => $targetUser->id,
        'name' => 'Usuario Actualizado',
    ]);
});

// 22. Admin: puede eliminar un usuario
test('it can delete a user as admin via endpoint', function () {
    $admin = crearUsuarioConRol('administrador');
    autenticar($admin);

    $targetUser = User::factory()->create();

    $this->deleteJson("/api/v1/users/{$targetUser->id}")
        ->assertNoContent();
});

// 23. Doctor: puede crear expediente medico
test('it can create medical history as doctor', function () {
    $medico = crearUsuarioConRol('medico');
    autenticar($medico);

    $pacient = Pacient::factory()->create();

    $this->postJson('/api/v1/medical-history', [
        'pacient_id' => $pacient->id,
        'weight' => 70.5,
        'height' => 1.72,
        'chronic_diseases' => 'none',
        'allergies' => 'none',
        'date_of_birth' => '2000-01-01',
        'medications' => 'none',
    ])->assertCreated();
});

// 24. Doctor: no puede crear expediente medico con informacion invalida
test("it can't create medical history with invalid information as doctor", function () {
    $medico = crearUsuarioConRol('medico');
    autenticar($medico);

    $pacient = Pacient::factory()->create();

    $this->postJson('/api/v1/medical-history', [
        'pacient_id' => $pacient->id,
        'weight' => 0,
        'height' => 1.72,
        'chronic_diseases' => 'none',
        'allergies' => 'none',
        'date_of_birth' => '2000-01-01',
        'medications' => 'none',
    ])->assertUnprocessable()
        ->assertJsonValidationErrors(['weight']);
});

// 25. Doctor: puede ver pacientes
test('it can view pacients as doctor', function () {
    $medico = crearUsuarioConRol('medico');
    autenticar($medico);

    $this->getJson('/api/v1/pacient')
        ->assertOk();
});

// 26. Doctor: puede ver expedientes medicos
test('it can view medical histories as doctor', function () {
    $medico = crearUsuarioConRol('medico');
    autenticar($medico);

    $this->getJson('/api/v1/medical-history')
        ->assertOk();
});

// 27.  Doctor: no puede crear paciente
test("it can't create pacient as doctor", function () {
    $medico = crearUsuarioConRol('medico');
    autenticar($medico);

    $this->postJson('/api/v1/pacient', [
        'name' => 'Ana',
        'lastname' => 'Ruiz',
        'dui' => '22345678-9',
        'phone_number' => '7223-4567',
        'gender' => 'female',
    ])->assertForbidden();
});

// 28. Doctor: no puede actualizar paciente
test("it can't update pacient as doctor", function () {
    $medico = crearUsuarioConRol('medico');
    autenticar($medico);

    $pacient = Pacient::factory()->create();

    $this->patchJson("/api/v1/pacient/{$pacient->id}", [
        'phone_number' => '7000-1234',
    ])->assertForbidden();
});

// 29. Doctor: puede ver su propio horario
test('it can view own schedule as doctor', function () {
    $medico = crearUsuarioConRol('medico');

    $schedule = Schedule::query()->create([
        'user_id' => $medico->id,
        'day_of_week' => 1,
        'start_at' => '09:00:00',
        'end_at' => '12:00:00',
    ]);

    expect($medico->can('view', $schedule))->toBeTrue();
});

// 30.  Doctor: no puede ver horario de otro medico
test("it can't view another doctor's schedule", function () {
    $medico = crearUsuarioConRol('medico');
    $otroMedico = crearUsuarioConRol('medico');

    $schedule = Schedule::query()->create([
        'user_id' => $otroMedico->id,
        'day_of_week' => 2,
        'start_at' => '09:00:00',
        'end_at' => '12:00:00',
    ]);

    expect($medico->can('view', $schedule))->toBeFalse();
});

// 31.  Asistente: puede crear paciente
test('it can create pacient as assistant', function () {
    $asistente = crearUsuarioConRol('asistente');
    autenticar($asistente);

    $this->postJson('/api/v1/pacient', [
        'name' => 'Diana',
        'lastname' => 'Pineda',
        'dui' => '42345678-9',
        'phone_number' => '7444-4567',
        'gender' => 'female',
    ])->assertCreated();
});

// 32.  Asistente: no puede crear paciente con informacion invalida
test("it can't create pacient with invalid information as assistant", function () {
    $asistente = crearUsuarioConRol('asistente');
    autenticar($asistente);

    $this->postJson('/api/v1/pacient', [
        'name' => 'Diana',
        'lastname' => '',
        'dui' => '52345678-9',
        'phone_number' => 'invalido',
        'gender' => 'female',
    ])->assertUnprocessable()
        ->assertJsonValidationErrors(['lastname', 'phone_number']);
});

// 33.  Asistente: puede actualizar paciente
test('it can update pacient as assistant', function () {
    $asistente = crearUsuarioConRol('asistente');
    autenticar($asistente);

    $pacient = Pacient::factory()->create();

    $this->patchJson("/api/v1/pacient/{$pacient->id}", [
        'phone_number' => '7555-1234',
    ])->assertOk();

    $this->assertDatabaseHas('pacients', [
        'id' => $pacient->id,
        'phone_number' => '7555-1234',
    ]);
});

// 34. Asistente: puede ver pacientes
test('it can view pacients as assistant', function () {
    $asistente = crearUsuarioConRol('asistente');
    autenticar($asistente);

    $this->getJson('/api/v1/pacient')
        ->assertOk();
});

// 35. Asistente: no puede ver expedientes medicos
test("it can't view medical histories as assistant", function () {
    $asistente = crearUsuarioConRol('asistente');
    autenticar($asistente);

    $this->getJson('/api/v1/medical-history')
    ->assertForbidden();
});

// 36. Asistente: no puede crear expediente medico
test("it can't create medical history as assistant", function () {
    $asistente = crearUsuarioConRol('asistente');
    autenticar($asistente);

    $pacient = Pacient::factory()->create();

    $this->postJson('/api/v1/medical-history', [
        'pacient_id' => $pacient->id,
        'weight' => 70.5,
        'height' => 1.72,
        'chronic_diseases' => 'none',
        'allergies' => 'none',
        'date_of_birth' => '2000-01-01',
        'medications' => 'none',
    ])->assertForbidden();
});

// 37.  Asistente: no puede actualizar expediente medico
test("it can't update medical history as assistant", function () {
    $asistente = crearUsuarioConRol('asistente');
    autenticar($asistente);

    $pacient = Pacient::factory()->create();
    $history = crearHistorialMedico($pacient);

    $this->patchJson("/api/v1/medical-history/{$history->id}", [
        'medications' => 'Ibuprofeno',
    ])->assertForbidden();
});

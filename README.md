# Sistema de Gestión de Citas Médicas - Índice y Guía del Proyecto

Repositorio del Backend y Panel Administrativo de la Clínica.

## 1. Entidades Core y Modelos de Base de Datos
- **Usuarios (y Roles):** [`app/Models/User.php`](./app/Models/User.php) - Modela Administradores, Médicos y Asistentes.
- **Pacientes:** [`app/Models/Pacient.php`](./app/Models/Pacient.php) - Almacena los datos personales de la clínica.
- **Expediente Clínico:** [`app/Models/MedicalHistory.php`](./app/Models/MedicalHistory.php) - Enlaza históricamente la información de un paciente (relación 1:1 o 1:N).
- **Citas Médicas:** [`app/Models/Appointment.php`](./app/Models/Appointment.php) - El núcleo que articula a un paciente con un médico en un horario específico.
- **Horarios/Agendas:** [`app/Models/Schedule.php`](./app/Models/Schedule.php) - Las agendas funcionales para cada médico.

## 2. API RESTful y Lógica de Citas
- **Rutas del API Endpoints:** [`routes/api.php`](./routes/api.php)
- **Controlador de Citas:** [`app/Http/Controllers/AppointmentController.php`](./app/Http/Controllers/AppointmentController.php) - Controla el flujo del `POST /api/appointments` para asegurar la correcta validación y persistencia de las reservas médicas.
- **Peticiones y Validaciones:** Directorio [`app/Http/Requests/`](./app/Http/Requests/) - Donde se incluye la robusta inspección y rechazo de citas cruzadas (`422 Unprocessable Entity`).

## 3. Seguridad y Autorización (Policies)
- **Control de Citas:** [`app/Policies/AppointmentPolicy.php`](./app/Policies/AppointmentPolicy.php) - (Ej: Restringe que el médico elimine citas).
- **Protección del Expediente:** [`app/Policies/MedicalHistoryPolicy.php`](./app/Policies/MedicalHistoryPolicy.php) - (Ej: Limita la capacidad de edición de los asistentes respecto a datos clínicos confidenciales).
- **Gestión Administrativa:** [`app/Policies/UserPolicy.php`](./app/Policies/UserPolicy.php) - Protege transversalmente para que únicamente un Administrador pueda afectar el acceso de los empleados.

## 4. Panel Administrativo TALL (Filament)
- **Gestión de Citas Administrativas:** Directorio [`app/Filament/Resources/Appointments`](./app/Filament/Resources/Appointments) - Presentación enfocada (el médico ve su agenda, el asistente controla la coordinación general).
- **Módulo de Pacientes (Infolists):** Directorio [`app/Filament/Resources/Pacients`](./app/Filament/Resources/Pacients) - Se listan expedientes con diseño elegante integrado.
- **Gestión Segura de Roles:** Directorio [`app/Filament/Resources/Users`](./app/Filament/Resources/Users) - Un recurso bloqueado en la navegación para roles subalternos.
- **Configuración del Panel y Dashboard Inicial:** [`app/Providers/Filament/AdminPanelProvider.php`](./app/Providers/Filament/AdminPanelProvider.php) - Integración de Stat Cards y Widgets estadísticos.

## 5. Población de Datos (Seeders y Factories)
Finalmente, para dar marcha al proyecto de forma instatánea con `php artisan migrate:seed`:
- **Seeder Principal:** [`database/seeders/DatabaseSeeder.php`](./database/seeders/DatabaseSeeder.php) - Genera desde cero los roles, las cuentas maestras de test, y llama de forma secuencial al resto de factorías.
- **Factories (Faker):** Dentro de [`database/factories/`](./database/factories/) habitan las lógicas para inventar cientos de médicos hipotéticos y citas esparcidas lógicamente.

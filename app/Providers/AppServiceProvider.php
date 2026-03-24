<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use App\Models\Appointment;
use App\Models\MedicalHistory;
use App\Models\Pacient;
use App\Models\Schedule;
use App\Models\User;
use App\Policies\AppointmentPolicy;
use App\Policies\MedicalHistoryPolicy;
use App\Policies\PacientPolicy;
use App\Policies\SchedulePolicy;
use App\Policies\UserPolicy;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::policy(Appointment::class, AppointmentPolicy::class);
        Gate::policy(MedicalHistory::class, MedicalHistoryPolicy::class);
        Gate::policy(Schedule::class, SchedulePolicy::class);
        Gate::policy(User::class, UserPolicy::class);
        Gate::policy(Pacient::class, PacientPolicy::class);
    }
}

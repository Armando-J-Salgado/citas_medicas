<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Http\Requests\StoreAppointmentRequest;
use App\Http\Requests\UpdateAppointmentRequest;
use App\Http\Resources\AppointmentResource;

class AppointmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // to get all appointments
        $appointments = Appointment::all();
        // to return the appointments as a resource collection
        return AppointmentResource::collection($appointments);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAppointmentRequest $request)
    {
        // to get the validated request data
        $validatedData = $request->validated();
        
        // to check if the doctor already has an appointment at this time
        $conflict = Appointment::where('doctor_id', $request->doctor_id)
            ->where('date', $request->date)
            ->where('time', $request->time)
            ->exists();

        // to reject the appointment if there is a conflict
        if ($conflict) {
            return response()->json([
                'message' => 'The doctor already has an appointment scheduled at this time.'
            ], 422);
        }

        // to create a new appointment
        $appointment = Appointment::create($validatedData);

        // to return the created appointment as a resource
        return new AppointmentResource($appointment);
    }

    /**
     * Display the specified resource.
     */
    public function show(Appointment $appointment)
    {
        // to return the specific appointment as a resource
        return new AppointmentResource($appointment);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Appointment $appointment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAppointmentRequest $request, Appointment $appointment)
    {
        // to get the validated request data
        $validatedData = $request->validated();

        // to update the appointment
        $appointment->update($validatedData);

        // to return the updated appointment as a resource
        return new AppointmentResource($appointment);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Appointment $appointment)
    {
        // to delete the appointment
        $appointment->delete();

        // to return a no content response
        return response()->json(null, 204);
    }
}

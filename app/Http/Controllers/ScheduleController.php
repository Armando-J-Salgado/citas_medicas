<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use App\Http\Requests\StoreScheduleRequest;
use App\Http\Requests\UpdateScheduleRequest;
use App\Http\Resources\ScheduleResource;

class ScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // to get all schedules
        $schedules = Schedule::all();
        // to return the schedules as a resource collection
        return ScheduleResource::collection($schedules);
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
    public function store(StoreScheduleRequest $request)
    {
        // to get the validated request data
        $validatedData = $request->validated();
        
        // to create a new schedule
        $schedule = Schedule::create($validatedData);

        // to return the created schedule as a resource
        return new ScheduleResource($schedule);
    }

    /**
     * Display the specified resource.
     */
    public function show(Schedule $schedule)
    {
        // to return the specific schedule as a resource
        return new ScheduleResource($schedule);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Schedule $schedule)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateScheduleRequest $request, Schedule $schedule)
    {
        // to get the validated request data
        $validatedData = $request->validated();

        // to update the schedule
        $schedule->update($validatedData);

        // to return the updated schedule as a resource
        return new ScheduleResource($schedule);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Schedule $schedule)
    {
        // to delete the schedule
        $schedule->delete();

        // to return a no content response
        return response()->json(null, 204);
    }
}

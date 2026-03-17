<?php

namespace App\Http\Controllers;

use App\Models\Pacient;
use App\Http\Requests\StorePacientRequest;
use App\Http\Requests\UpdatePacientRequest;

class PacientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
    public function store(StorePacientRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Pacient $pacient)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Pacient $pacient)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePacientRequest $request, Pacient $pacient)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pacient $pacient)
    {
        //
    }
}

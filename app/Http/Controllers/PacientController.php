<?php

namespace App\Http\Controllers;

use App\Models\Pacient;
use App\Http\Requests\StorePacientRequest;
use App\Http\Requests\UpdatePacientRequest;
use App\Http\Resources\PacientResource;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class PacientController extends Controller
{
    use AuthorizesRequests;
    public function __construct() {
        $this->authorizeResource(Pacient::class);
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', Pacient::class);
        $request->validate([
            'dui'=> 'regex:/^\d{8}-\d$/',
        ]);

        $pacients=Pacient::query()
        ->when($request->has('dui'), 
            fn ($query)=>$query->where('dui', $request->input('dui')))

        ->get();

        return PacientResource::collection($pacients);
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
        $this->authorize('create', Pacient::class);
        $data = $request->validated();

        $pacient = Pacient::create($data);

        return response()->json(PacientResource::make($pacient), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Pacient $pacient)
    {
        $this->authorize('view', $pacient);
        
        return response()->json(PacientResource::make($pacient));
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
        $this->authorize('update', $pacient);
        $data = $request->validated();
        $pacient->update($data);
        return response()->json(PacientResource::make($pacient));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pacient $pacient)
    {
        //
    }
}

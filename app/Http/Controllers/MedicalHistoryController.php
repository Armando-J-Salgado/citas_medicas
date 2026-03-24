<?php

namespace App\Http\Controllers;

use App\Models\MedicalHistory;
use App\Http\Requests\StoreMedicalHistoryRequest;
use App\Http\Requests\UpdateMedicalHistoryRequest;
use App\Http\Resources\MedicalHistoryResource;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class MedicalHistoryController extends Controller
{
    use AuthorizesRequests;
    public function __construct() {
        $this->authorizeResource(MedicalHistory::class,  'medicalHistory');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this ->authorize('viewAny', MedicalHistory::class);
        $request->validate([
            'pacient_id' => 'sometimes|exists:pacients,id',
        ]);

        $medicalHistories = MedicalHistory::query()
        
        ->when($request->has('pacient_id'), 
            fn ($query)=>$query->where('pacient_id', $request->input('pacient_id')))

        ->get();

        return MedicalHistoryResource::collection($medicalHistories);
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
    public function store(StoreMedicalHistoryRequest $request)
    {
        $this->authorize('create', MedicalHistory::class);
        $data = $request->validated();

        $medicalHistory = MedicalHistory::create($data);

        return response()->json(MedicalHistoryResource::make($medicalHistory), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(MedicalHistory $medicalHistory)
    {
        $this->authorize('view', $medicalHistory);
        return response()->json(MedicalHistoryResource::make($medicalHistory));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MedicalHistory $medicalHistory)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMedicalHistoryRequest $request, MedicalHistory $medicalHistory)
    {
        $this->authorize('update', $medicalHistory);
        $data = $request->validated();
        $medicalHistory->update($data);
        return response()->json(MedicalHistoryResource::make($medicalHistory));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MedicalHistory $medicalHistory)
    {
        //
    }
}

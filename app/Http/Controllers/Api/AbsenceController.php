<?php

namespace App\Http\Controllers\Api;

use App\Models\Absence;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AbsenceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function absences()
    {
        //
        $user = auth()->user();
    
        if ($user->isAdmin) {
            $absences = Absence::all();
        } 
        
        if (!$user->isAdmin) {
            $absences = $user->absences;
        }
        
        return response()->json($absences);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function createAbsence()
    {
        //
        $user = auth()->user();

        if ($user->isAdmin && request('user_id')) {
            $user_id = request('user_id');
        } 
        
        if (!$user->isAdmin) {
            $user_id = $user->id;
        }

        $absence = Absence::create([
            'user_id' => $user_id,
            'startingDate' => request('startingDate'),
            'endingDate'=> request('endingDate'),
            'startingTime'=> request('startingTime'),
            'endingTime'=> request('endingTime'),
            'addDocument'=> request('addDocument'),
            'description'=> request('description'),
        ]);

        $absence->save();
        
        return response()->json(['message' => 'Ausencia solicitada exitosamente', 'absence' => $absence], 201);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function updateAbsence(Request $request, string $id)
    {
        //
        $user = auth()->user();

        $absence = Absence::find($id);

        // Verificar que la ausencia pertenece a esa persona
        if ($user->id !== $absence->user_id) {
            // Verificar si es admin 
            if(!$user->isAdmin) {
                return response()->json(['message' =>'No tienes permiso para modificar esta ausencia'], 403);
            }
        }

        // Modificar la ausencia

        $absence->update([
            'startingDate' => request('startingDate'),
            'endingDate'=> request('endingDate'),
            'startingTime'=> request('startingTime'),
            'endingTime'=> request('endingTime'),
            'addDocument'=> request('addDocument'),
            'description'=> request('description'),
        ]);

        // Guardar la modificación
        $absence->save();

        return response()->json(['message' => 'Ausencia modificada correctamente'], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function deleteAbsence(string $id)
    {
        //
        $user = auth()->user();

        $absence = Absence::find($id);

        // Verificar que la ausencia pertenece a esa persona
        if ($user->id !== $absence->user_id) {
            // Verificar si es admin 
            if(!$user->isAdmin) {
                return response()->json(['message' =>'No tienes permiso para borrar esta ausencia'], 403);
            }
        }

        // Borrar la ausencia
        $absence->delete();

        return response()->json(['message' => 'Ausencia borrada correctamente'], 200);
    }
}


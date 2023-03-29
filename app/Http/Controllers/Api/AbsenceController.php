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
     * Display the specified resource.
     */
    public function showAbsence(string $id)
    {
        $user = auth()->user();

        $absence = Absence::find($id);

        if ($user->id !== $absence->user_id) {
            if(!$user->isAdmin) {
                return response()->json(['message' =>'No tienes permiso para ver esta ausencia'], 403);
            }
        }
        
        return response()->json($absence);
    }

    /**
     * Update the specified resource in storage.
     */
    public function updateAbsence(Request $request, string $id)
    {
        $user = auth()->user();

        $absence = Absence::find($id);

        if ($user->id !== $absence->user_id) {
            if(!$user->isAdmin) {
                return response()->json(['message' =>'No tienes permiso para modificar esta ausencia'], 403);
            }
        }

        $absence->update([
            'startingDate' => request('startingDate'),
            'endingDate'=> request('endingDate'),
            'startingTime'=> request('startingTime'),
            'endingTime'=> request('endingTime'),
            'addDocument'=> request('addDocument'),
            'description'=> request('description'),
        ]);

        $absence->save();

        return response()->json(['message' => 'Ausencia modificada correctamente'], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function deleteAbsence(string $id)
    {
        $user = auth()->user();

        $absence = Absence::find($id);

        if ($user->id !== $absence->user_id) {
            if(!$user->isAdmin) {
                return response()->json(['message' =>'No tienes permiso para borrar esta ausencia'], 403);
            }
        }

        $absence->delete();

        return response()->json(['message' => 'Ausencia borrada correctamente'], 200);
    }
}
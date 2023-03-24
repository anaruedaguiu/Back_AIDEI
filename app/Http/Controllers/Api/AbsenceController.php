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
    public function index()
    {
        //
        $user = auth()->user();
    
        if ($user->isAdmin) {
            $absences = Absence::all();
        } else {
            $absences = $user->absences;
        }
        
        return response()->json($absences);
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
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function deleteAbsence(string $id)
    {
        //
        $user = auth()->user();

        $absence = Absence::find($id);

        // Verificar que el usuario es el dueño de la ausencia
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

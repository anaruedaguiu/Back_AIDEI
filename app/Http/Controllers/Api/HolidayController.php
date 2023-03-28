<?php

namespace App\Http\Controllers\Api;

use App\Models\Holiday;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class HolidayController extends Controller
{
    
    public function holidays()
    {
        $user = auth()->user();
    
        if ($user->isAdmin) {
            $holidays = Holiday::all();
        } 
        
        if (!$user->isAdmin) {
            $holidays = $user->holidays;
        }
        
        return response()->json($holidays);
    }

    public function createHoliday()
    {
        $user = auth()->user();

        if ($user->isAdmin && request('user_id')) {
            $user_id = request('user_id');
        } 
        
        if (!$user->isAdmin) {
            $user_id = $user->id;
        }

        $holiday = Holiday::create([
            'user_id' => $user_id,
            'startingDate' => request('startingDate'),
            'endingDate'=> request('endingDate'),
        ]);

        $holiday->save();
        
        return response()->json(['message' => 'Vacaciones solicitadas exitosamente', 'holiday' => $holiday], 201);
    }

    public function showHoliday(string $id)
    {
        $user = auth()->user();

        $holiday = Holiday::find($id);

        if ($user->id !== $holiday->user_id) {
            // Verificar si es admin 
            if(!$user->isAdmin) {
                return response()->json(['message' =>'No tienes permiso para ver este periodo de vacaciones'], 403);
            }
        }
        
        return response()->json($holiday);
    }

    public function update(Request $request, string $id)
    {
        
    }

    public function destroy(string $id)
    {
        
    }
}

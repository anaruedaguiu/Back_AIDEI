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

    public function show(string $id)
    {
        
    }

    public function update(Request $request, string $id)
    {
        
    }

    public function destroy(string $id)
    {
        
    }
}

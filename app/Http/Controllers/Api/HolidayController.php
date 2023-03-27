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

    public function store(Request $request)
    {
        
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

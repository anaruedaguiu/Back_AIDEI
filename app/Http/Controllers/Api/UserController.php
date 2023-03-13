<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Middleware\IsAdmin;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Verificar si el usuario actual es un administrador
        if (auth()->user()->isAdmin()) {
            // Si el usuario es un administrador, obtener todos los usuarios registrados en la base de datos
            $users = User::all();

            // Devolver los usuarios en formato JSON
            return response()->json([$users,200]);
        }

        // Si el usuario no es un administrador, obtener su perfil
        if (auth()->check()) {
            $user = auth()->user();
            $me = $user->me;

            // Devolver el perfil del usuario en formato JSON
            return response()->json([$me,200]);
        }

        // Si el usuario no está autenticado, devolver una respuesta de error
        return response()->json(['error' => 'Unauthorized'], 401);
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
    public function destroy(string $id)
    {
        //
    }
}

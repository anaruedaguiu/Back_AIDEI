<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

use App\Models\User;
use Illuminate\Http\Request;


class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login']]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login()
    {
        $credentials = request(['email', 'password']);

        if (!$token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Usuario no autorizado'], 401);
        }
        // User es necesario para saber si es admin o no y usar el resto de datos del user
        $user = Auth::user();

        return $this->respondWithToken($token, $user);
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function profile(string $id)
    {
        $user = User::find($id);

        if (auth()->user()->isAdmin) {
            // Si el usuario actual es un administrador, puede ver cualquier perfil
            return response()->json($user);
        }

        if (auth()->id() !== $user->id) {
            // Si el usuario actual no es el dueño del perfil, devuelve un error 403
            return response()->json(['message' =>'No tienes permiso para ver este perfil'], 403);
        }

        // Si el usuario actual es el dueño del perfil, puede ver su propio perfil
        return response()->json($user);
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Sesión cerrada exitosamente']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     * @param  User $user Los datos de usuario (por defecto null)
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token, $user = null)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
            'user' => $user
        ]);
    }
}
<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function __construct()
    {
        $this->middleware(['auth:api']);
    }

    
    public function index(Request $request)
    {
        $user = auth()->user();

        if ($user->isAdmin) {
            $users = User::all();
            return response()->json($users);
        }

        if ($user) {
            return response()->json($user);
        }

        return response()->json(['error' => 'Unauthorized'], 401);
    }


    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'=> 'required',
            'surname'=> 'required',
            'email' => 'required|string|email|max:100|unique:users',
            'password' => 'required|string|min:6'
        ]); 

        if($validator->fails()){
            return response()->json($validator->errors()->toJson(),400);
        }

        $user = User::create(array_merge(
            $validator->validated(),
            ['password' => bcrypt($request->password)]
        ));

        return response()->json([
            'message' => '¡Usuario registrado exitosamente!',
            'user' => $user
        ],201);
    }
}

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
    
    public function dashboard(Request $request)
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

    public function registerEmployee(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'=> 'required',
            'surname'=> 'required',
            'email' => 'required|string|email|max:100|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'phone' => 'nullable',
            'idNumber' => 'nullable',
            'sector' => 'nullable',
            'image' => 'nullable',
            'startingDate' => 'nullable',
            'endingDate' => 'nullable',
            'active' => 'nullable',
            'contractType' => 'nullable',
            'isAdmin' => 'boolean'
        ]); 

        if($validator->fails()){
            return response()->json($validator->errors()->toJson(),400);
        }

        $user = User::create(array_merge(
            $validator->validated(),
            ['password' => bcrypt($request->password)]
        ));


        return response()->json([
            'message' => '¡Registro realizado exitosamente!',
            'user' => $user
        ],201);
    }

    public function deleteEmployee ($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['error' => 'Registro no encontrado'], 404);
        }

        if(!auth()->user()->isAdmin) {
            return response()->json(['error' => 'No autorizado'], 403);
        }
        
        $user->delete();
        
        return response()->json(['message' => 'Registro eliminado exitosamente'], 200);
        return response()->json($user, 200);
    } 

    public function updateEmployee(Request $request, $id)
    {
        //
        $user = User::find($id);

        $user->update([
            'name' => $request->name,
            'surname'=> $request->surname,
            'email'=> $request->email,
            'password'=> $request->password,
        ]);

        $user->save();
        
        return response()->json($user, 200);
    }
}


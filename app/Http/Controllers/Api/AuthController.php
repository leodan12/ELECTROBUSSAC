<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


final class AuthController extends Controller
{
    final public function login(): JsonResponse
    {
        try {
            request()->validate([
                'email' => 'required|email',
                'password' => 'required'
            ]);

            $user = User::where('email', request()->email)->first();

            if (Auth::attempt(request()->only('email', 'password'))) {
                if ($user->status == "activo") {
                    return response()->json([
                        'name' => $user->name,
                        'email' => $user->email,
                        'token' => $user->createToken('auth_token')->plainTextToken,
                        'message' => 'Logueado'
                    ], 200);
                } else {
                    return response()->json([
                        'message' => 'Usuario Suspendido'
                    ], 403);
                }
            } else {
                return response()->json([
                    'message' => 'Correo o Contraseña Incorrectos'
                ], 401);
            }
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al realizar la acción'
            ], 500);
        }
    }

    final public function register()
    {
        //se valida la información que viene en $request
        request()->validate([
            'name' => 'required|string|max:50',
            'email' => 'required|string|max:80|email',
            'password' => 'required|string|min:7'
        ]);
        $users = User::where('email', '=', request()->email)->get();
        if (count($users) == 0) {
            $user = User::create([
                'name' => request()->name,
                'email' => request()->email,
                'password' => Hash::make(request()->password),
                'status' => 'activo'
            ]);
            $user->assignRole(2);
            return response()->json([
                'name' => $user->name,
                'email' => $user->email,
                'token' => $user->createToken('auth_token')->plainTextToken,
                'message' => 'Registrado'
            ], 200);
        } else {
            return response()->json([
                "message" => "Email Duplicado",
            ], 409);
        }
    }
    final public function usuariologueado(Request $request): JsonResponse
    {
        try {
            $user =  $request->user();
            if ($user) {
                return response()->json([
                    'name' => $user->name,
                    'email' => $user->email,
                    'message' => 'Usuario Actual'
                ], 200);
            } else {
                return response()->json([
                    "message" => "No Logueado",
                ], 404);
            }
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al realizar la acción'
            ], 500);
        }
    }

    final public function logout(Request $request): JsonResponse
    {
        //auth()->user()->currentAccessToken()->delete(); 
        try {
            $request->user()->tokens()->delete();
            return response()->json([
                'message' => 'Sesión cerrada'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al realizar la acción'
            ], 500);
        }
    }
}

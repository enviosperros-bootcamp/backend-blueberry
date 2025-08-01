<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Location;
use App\Models\Specialty;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\DB;
use \Throwable;

class AuthController extends Controller
{

    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60,
            'user' => auth('api')->user()
        ]);
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'required|string|max:20',
            'role' => 'required|in:doctor,patient',
            'birthdate' => 'required|date',
            'address' => 'nullable|array',
            'address.*.address' => 'nullable|string',
            'address.*.city' => 'nullable|string',
            'specialties' => 'nullable|array',
        ]);

        DB::beginTransaction();
        try {
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => $validated['password'],
                'phone' => $validated['phone'],
                'role' => $validated['role'],
                'birthdate' => $validated['birthdate'],
            ]);

            if (!empty($validated['address'])) {
                foreach ($validated['address'] as $location) {
                    $user->locations()->create($location);
                }
            }

        // cambios location
        if (!empty($validated['address']) && $user->role === 'doctor') {
            foreach ($validated['address'] as $location) {
                $existingLocation = $user->locations()
                    ->where('address', $location['address'] ?? null)
                    ->where('city', $location['city'] ?? null)
                    ->first();

                if (!$existingLocation) {
                    $user->locations()->create($location);
                }
            }
        }

        // especialidades
        // solo si el usuario es un doctor y se han proporcionado especialidades
        if (!empty($validated['specialties']) && $user->role === 'doctor') {
            $specialtyIds = Specialty::whereIn('name', $validated['specialties'])->pluck('id')->toArray();
            $user->specialties()->sync($specialtyIds);
        }

            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();
            throw $e;
        }


        $token = JWTAuth::fromUser($user);

        return response()->json([
            'message' => 'User registered successfully',
            'user' => $user->load(['locations', 'specialties']),
            'token' => $token
        ], 201);
    }

    public function login(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email|exists:users,email',
            'password' => 'required|string',
        ]);

        if (!$token = auth('api')->attempt($validated)) {
            return response()->json([
                'message' => 'Invalid credentials'
            ], 401);
        }

        return $this->respondWithToken($token);
    }

    public function logout()
    {
        // invalidar el token actual
        auth('api')->logout();

        return response()->json([
            'message' => 'Successfully logged out'
        ]);
    }

    public function refresh()
    {
        return $this->respondWithToken(auth('api')->refresh());
    }

    public function me()
    {
        return response()->json([
            'user' => auth('api')->user()
        ]);
    }
}

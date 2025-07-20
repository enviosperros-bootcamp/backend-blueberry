<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class DoctorController extends Controller
{
    public function index(Request $request)
    {
        $city = $request->query('city');
        $specialty = $request->query('specialty');

        $doctors = User::with(['specialties', 'locations'])
            ->where('role', 'doctor')
            ->when($city, function ($query, $city) {
                $query->whereHas('locations', function ($q) use ($city) {
                $q->whereRaw('LOWER(city) = ?', [strtolower($city)]);
                });
            })
            ->when($specialty, function ($query, $specialty) {
            $query->whereHas('specialties', function ($q) use ($specialty) {
                $q->whereRaw('LOWER(name) = ?', [strtolower($specialty)]);
            });
        })
            ->get()
            ->map(function ($doctor) {
                return [
                    'id' => $doctor->id,
                    'name' => $doctor->name,
                    'email' => $doctor->email,
                    'phone' => $doctor->phone,
                    'birthdate' => $doctor->birthdate->toDateString(),
                    'specialties' => $doctor->specialties->pluck('name'),
                    'locations' => $doctor->locations->map(function ($location) {
                        return [
                            'city' => $location->city,
                            'address' => $location->address,
                        ];
                    }),
                ];
            });

        return response()->json($doctors);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\DoctorInfo;

class DoctorInfoController extends Controller
{
public function upsertDoctorInfo(Request $request)
{
    $request->validate([
        'doctor_id' => 'required|exists:users,id',
        'professional_license' => 'required|string',
        'specialty_ids' => 'required|array',
        'service_ids' => 'required|array',
    ]);

    $doctor = User::findOrFail($request->doctor_id);

    $doctorInfo = DoctorInfo::updateOrCreate(
        ['user_id' => $doctor->id],
        ['professional_license' => $request->professional_license]
    );

    $specialties = array_map('intval', $request->input('specialty_ids', []));
    $services = array_map('intval', $request->input('service_ids', []));

    $doctor->specialties()->sync($specialties);
    $doctor->services()->sync($services);

    return response()->json([
        'message' => 'Doctor info guardada con éxito',
        'doctor_info' => $doctorInfo->load('user.specialties', 'user.services'),
    ]);
}


public function show($id)
{
    $doctorInfo = DoctorInfo::with('user.specialties', 'user.services', 'user.locations')->where('user_id', $id)->first();

    if (!$doctorInfo) {
        return response()->json(['message' => 'Doctor no encontrado'], 404);
    }

    return response()->json($doctorInfo);
}

}

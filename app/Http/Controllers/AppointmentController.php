<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    // Crear una nueva cita (POST /api/appointments)
    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:users,id',  // Verificar que el paciente exista
            'service_id' => 'required|exists:services,id',  // Verificar que el servicio exista
            'date' => 'required|date|after:today',  // Validar que la fecha sea futura
            'motive' => 'required|string',
        ]);

        $appointment = Appointment::create([
            'patient_id' => $validated['patient_id'],
            'service_id' => $validated['service_id'],
            'date' => $validated['date'],
            'motive' => $validated['motive'],
        ]);

        return response()->json([
            'message' => 'Appointment created successfully',
            'appointment' => $appointment
        ], 201);
    }

    // Obtener todas las citas (GET /api/appointments)
    public function index()
    {
         $appointments = Appointment::with(['patient', 'service.doctor'])->get();

        return response()->json([
            'appointments' => $appointments
        ]);
    }

    // Obtener las citas de un paciente específico (GET /api/appointments?patient_id=X)
    public function getByPatient(Request $request)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:users,id',
        ]);

        $appointments = Appointment::where('patient_id', $validated['patient_id'])->get();

        return response()->json([
            'appointments' => $appointments
        ]);
    }

    // Eliminar una cita (DELETE /api/appointments/{id})
    public function destroy($id)
    {
        $appointment = Appointment::findOrFail($id);  // Buscar la cita por su ID
        $appointment->delete();  // Eliminar la cita

        return response()->json([
            'message' => 'Appointment deleted successfully'
        ]);
    }

    // Actualizar el estatus de una cita (PUT /api/appointments/{id})
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,confirmed,completed,cancelled',  // Validar que el estado sea uno de los siguientes
        ]);

        $appointment = Appointment::findOrFail($id);  // Buscar la cita por su ID

        // Actualizar el estado de la cita
        $appointment->status = $validated['status'];
        $appointment->save();

        return response()->json([
            'message' => 'Appointment status updated successfully',
            'appointment' => $appointment
        ]);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use App\Models\Appointment;
use Illuminate\Http\Request;

class FeedbackController extends Controller
{
     // Crear un feedback 
    public function store(Request $request, $appointment_id)
    {
        // Validación de los datos
        $validated = $request->validate([
            'text' => 'required|string',
            'score' => 'required|integer|min:1|max:5', 
        ]);

        // Obtener la cita para conseguir el doctor_id
        $appointment = Appointment::findOrFail($appointment_id);

        // Obtener el doctor (usuario) asociado a esta cita
        $doctor = $appointment->doctor; // Relación con el modelo User (doctor)

        // Crear el feedback
        $feedback = Feedback::create([
            'user_id' => $appointment->patient_id,
            'doctor_id' => $doctor->id,
            'text' => $validated['text'],
            'score' => $validated['score'],
            'appointment_id' => $appointment_id,
        ]);

        return response()->json([
            'message' => 'Feedback created successfully',
            'feedback' => $feedback
        ], 201);
    }

    // Obtener feedbacks de un doctor específico (GET /api/feedbacks?user_id=X)
    public function index(Request $request)
    {
        // Validar que el user_id esté presente
        $validated = $request->validate([
            'doctor_id' => 'required|exists:users,id', 
        ]);

        // Obtener todos los feedbacks de ese doctor
        $feedbacks = Feedback::where('doctor_id', $validated['doctor_id'])->get();

        // Verificar si hay feedbacks
        if ($feedbacks->isEmpty()) {
            return response()->json([
                'message' => 'No hay feedbacks de este doctor'
            ], 404);
        }

        return response()->json([
            'feedbacks' => $feedbacks
        ]);
    }
}

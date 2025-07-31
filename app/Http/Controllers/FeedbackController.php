<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use Illuminate\Http\Request;

class FeedbackController extends Controller
{
     // Crear un feedback 
    public function store(Request $request)
    {
        // Validación de los datos
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'text' => 'required|string',
            'score' => 'required|integer|min:1|max:5', 
        ]);

        // Crear el feedback
        $feedback = Feedback::create([
            'user_id' => $validated['user_id'],
            'text' => $validated['text'],
            'score' => $validated['score'],
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
            'user_id' => 'required|exists:users,id', 
        ]);

        // Obtener todos los feedbacks de ese doctor
        $feedbacks = Feedback::where('user_id', $validated['user_id'])->get();

        return response()->json([
            'feedbacks' => $feedbacks
        ]);
    }
}

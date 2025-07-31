<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    // Crear un nuevo servicio (POST /api/services)
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'doctor_id' => 'required|exists:users,id',  // Verificar que el doctor exista
            'service_type' => 'required|string|max:255',
        ]);

        $service = Service::create([
            'name' => $validated['name'],
            'price' => $validated['price'],
            'doctor_id' => $validated['doctor_id'],
            'service_type' => $validated['service_type'],
        ]);

        return response()->json([
            'message' => 'Service created successfully',
            'service' => $service
        ], 201);
    }

    // Obtener todos los servicios (GET /api/services)
    public function index()
    {
        $services = Service::all();  // Obtener todos los servicios

        return response()->json([
            'services' => $services
        ]);
    }

    // Obtener un servicio específico (GET /api/services/{id})
    public function show($id)
    {
        $service = Service::findOrFail($id);  // Buscar el servicio por su ID

        return response()->json([
            'service' => $service
        ]);
    }

    // Actualizar un servicio (PUT /api/services/{id})
    public function update(Request $request, $id)
    {
        $service = Service::findOrFail($id);  // Buscar el servicio por su ID

        // Validar los datos del servicio
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'doctor_id' => 'required|exists:users,id',  // Verificar que el doctor exista
            'service_type' => 'required|string|max:255',
        ]);

        // Actualizar el servicio
        $service->update($validated);

        return response()->json([
            'message' => 'Service updated successfully',
            'service' => $service
        ]);
    }

    // Eliminar un servicio (DELETE /api/services/{id})
    public function destroy($id)
    {
        $service = Service::findOrFail($id);  // Buscar el servicio por su ID
        $service->delete();  // Eliminar el servicio

        return response()->json([
            'message' => 'Service deleted successfully'
        ]);
    }
}

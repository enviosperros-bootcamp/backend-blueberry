<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\Http\Request;
use App\Models\Service;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\User;

class AppointmentController extends Controller
{
    // Crear una nueva cita (POST /api/appointments)
    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:users,id',
            'service_id' => 'required|exists:services,id',
            'date' => 'required|date|after:now',
            'motive' => 'required|string',
        ]);

        $service = Service::findOrFail($validated['service_id']);
        $doctorId = $service->doctor_id;
        $appointmentDate = new \DateTime($validated['date']);

        // Definir el rango de 1 hora antes y después
        $startRange = (clone $appointmentDate)->modify('-1 hour');
        $endRange = (clone $appointmentDate)->modify('+1 hour');

        // Buscar citas del doctor en ese rango
        $conflict = Appointment::where('doctor_id', $doctorId)
            ->whereBetween('date', [$startRange->format('Y-m-d H:i:s'), $endRange->format('Y-m-d H:i:s')])
            ->exists();

        if ($conflict) {
            return response()->json([
                'message' => 'El doctor ya tiene una cita dentro de una hora de esta hora. Por favor elige otro horario.'
            ], 422);
        }

        $appointment = Appointment::create([
            'patient_id' => $validated['patient_id'],
            'doctor_id'  => $doctorId,
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

        $appointments = Appointment::with(['doctor', 'service'])
            ->where('patient_id', $validated['patient_id'])
            ->get();

        return response()->json([
            'appointments' => $appointments
        ]);
    }

    public function getByDoctor(Request $request)
    {
        $validated = $request->validate([
            'doctor_id' => 'required|exists:users,id',
        ]);

        $appointments = Appointment::with(['patient', 'service'])
            ->where('doctor_id', $validated['doctor_id'])
            ->get();

        return response()->json([
            'appointments' => $appointments
        ]);
    }

    // Eliminar una cita (DELETE /api/appointments/{id})
    public function destroy($id)
    {
        $appointment = Appointment::findOrFail($id);
        $appointment->delete();

        return response()->json(['message' => 'Cita cancelada correctamente']);
    }

    // Actualizar el estatus de una cita (PUT /api/appointments/{id})
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'date' => 'required|date|after:now',
        ]);

        $appointment = Appointment::findOrFail($id);
        $appointment->date = $validated['date'];
        $appointment->save();

        return response()->json([
            'message' => 'Cita reprogramada correctamente',
            'appointment' => $appointment
        ]);
    }


    /* Estadisticas */

    public function monthlyAppointmentsByDoctor(Request $request)
    {
        $doctorId = $request->user()->id; // Obtener el ID del doctor autenticado jwt

        // Contar citas por mes en el año actual para ese doctor
        $currentYear = date('Y');

        $data = Appointment::selectRaw('MONTH(date) as month, COUNT(*) as total')
            ->where('doctor_id', $doctorId)
            ->whereYear('date', $currentYear)
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Inicializar arreglo con 12 meses a 0
        $monthlyCounts = array_fill(0, 12, 0);

        // Mapear resultados a arreglo base 0 (enero = 0)
        foreach ($data as $row) {
            $monthlyCounts[$row->month - 1] = $row->total;
        }

        return response()->json([
            'year' => $currentYear,
            'monthlyCounts' => $monthlyCounts,
        ]);
    }

    public function weeklyAppointmentsByDoctor(Request $request)
    {
        $doctorId = $request->user()->id;

        // Contar citas por día de la semana (1=Lunes ... 7=Domingo)
        $data = Appointment::selectRaw('DAYOFWEEK(date) as weekday, COUNT(*) as total')
            ->where('doctor_id', $doctorId)
            ->whereBetween('date', [
                now()->startOfWeek(),
                now()->endOfWeek()
            ])
            ->groupBy('weekday')
            ->get();

        // Array base con 7 días (Lun..Dom) iniciando en 0
        $dailyCounts = array_fill(0, 7, 0);

        foreach ($data as $row) {
            // DAYOFWEEK en MySQL devuelve: 1=Dom, 2=Lun, ..., 7=Sáb
            // Reajustamos para que el índice 0 sea Lunes
            $index = $row->weekday == 1 ? 6 : $row->weekday - 2;
            $dailyCounts[$index] = $row->total;
        }

        return response()->json([
            'week' => [
                'start' => now()->startOfWeek()->toDateString(),
                'end'   => now()->endOfWeek()->toDateString()
            ],
            'dailyCounts' => $dailyCounts
        ]);
    }

    public function newPatientsPerMonthForDoctor(Request $request)
    {
        $doctorId = $request->user()->id;
        $currentYear = date('Y');

        $data = Appointment::selectRaw('MONTH(MIN(date)) as month, patient_id')
            ->join('services', 'appointments.service_id', '=', 'services.id')
            ->where('services.doctor_id', $doctorId)
            ->whereYear('appointments.date', $currentYear)
            ->groupBy('patient_id')
            ->get();

        $monthlyCounts = array_fill(0, 12, 0);

        foreach ($data as $row) {
            $monthlyCounts[$row->month - 1] += 1;
        }

        return response()->json([
            'year' => $currentYear,
            'monthlyCounts' => $monthlyCounts
        ]);
    }

    public function ageDistributionByDoctor(Request $request)
    {
        $doctorId = $request->user()->id;

        $patients = User::select('users.birthdate')
            ->join('appointments', 'users.id', '=', 'appointments.patient_id')
            ->join('services', 'appointments.service_id', '=', 'services.id')
            ->where('services.doctor_id', $doctorId)
            ->distinct()
            ->get();

        $ageRanges = [
            '0-10' => 0,
            '11-20' => 0,
            '21-30' => 0,
            '31-40' => 0,
            '41-50' => 0,
            '51+' => 0,
        ];

        foreach ($patients as $patient) {
            if (!$patient->birthdate) continue;

            $age = Carbon::parse($patient->birthdate)->age;

            if ($age <= 10) {
                $ageRanges['0-10']++;
            } elseif ($age <= 20) {
                $ageRanges['11-20']++;
            } elseif ($age <= 30) {
                $ageRanges['21-30']++;
            } elseif ($age <= 40) {
                $ageRanges['31-40']++;
            } elseif ($age <= 50) {
                $ageRanges['41-50']++;
            } else {
                $ageRanges['51+']++;
            }
        }

        return response()->json([
            'categories' => array_keys($ageRanges),
            'counts' => array_values($ageRanges),
        ]);
    }


    public function serviceDistributionByDoctor(Request $request)
    {
        $doctorId = $request->user()->id;

        $data = Appointment::selectRaw('services.name as service, COUNT(*) as total')
            ->join('services', 'appointments.service_id', '=', 'services.id')
            ->where('services.doctor_id', $doctorId)
            ->groupBy('services.name')
            ->get();

        $labels = [];
        $counts = [];

        foreach ($data as $row) {
            $labels[] = $row->service;
            $counts[] = $row->total;
        }

        return response()->json([
            'labels' => $labels,
            'series' => $counts
        ]);
    }


    // Método para obtener citas por doctor fullcalendar
    public function appointmentsByDoctor(Request $request)
    {
        $doctorId = $request->user()->id;

        $appointments = Appointment::with(['patient', 'service'])
            ->whereHas('service', function ($q) use ($doctorId) {
                $q->where('doctor_id', $doctorId);
            })
            ->get()
            ->map(function ($appointment) {
                return [
                    'id' => $appointment->id,
                    'title' => $appointment->service->name,
                    'patient' => $appointment->patient->name,
                    'cause' => $appointment->motive,
                    'start' => $appointment->date,
                ];
            });

        return response()->json($appointments);
    }

    // Futuras consultas para un paciente
    public function upcomingByPatient()
    {
        $patientId = auth()->id(); // se obtiene del JWT automáticamente
        $today = now();

        $appointments = Appointment::with(['doctor', 'service'])
            ->where('patient_id', $patientId)
            ->where('date', '>=', $today)
            ->orderBy('date', 'asc')
            ->get();

        return response()->json([
            'appointments' => $appointments
        ]);
    }
}

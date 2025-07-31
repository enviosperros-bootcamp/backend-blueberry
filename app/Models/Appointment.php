<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    protected $fillable = [
        'patient_id',
        'service_id',
        'date',
        'motive',
        'doctor_id'
    ];

    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }


    // Relación con el modelo User - Paciente
    public function patient()
    {
        return $this->belongsTo(User::class, 'patient_id');
    }


    // Relación con el modelo Service
    public function service()
    {
        return $this->belongsTo(Service::class);
    }
}

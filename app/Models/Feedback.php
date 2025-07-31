<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    protected $fillable = [
        'doctor_id',
        'user_id', 
        'text', 
        'score',
        'appointment_id'
    ];

    // Relación con el paciente (usuario)
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id'); 
    }

    // Relación con el doctor (usuario)
    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }

    // Relación con la cita
    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }
}

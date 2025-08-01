<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DoctorInfo extends Model
{
    protected $fillable = [
        'user_id',
        'professional_license',
        'feedback_id'
    ];

    public function specialties()
    {
        return $this->belongsToMany(Specialty::class, 'doctor_specialty', 'user_id', 'specialty_id');
    }

    // Relación con el modelo User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    

    // Relación con el modelo Specialty 
    public function specialty()
    {
        return $this->belongsTo(Specialty::class);
    }

    // Relación con el modelo Service 
    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    // Relación con el modelo Feedback 
    public function feedbacks()
    {
        return $this->hasMany(Feedback::class);
    }

    
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    protected $fillable = [
        'doctor_id', 
        'text', 
        'score'
    ];

    // Relación con el modelo User - Doctor
    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id'); 
    }
}

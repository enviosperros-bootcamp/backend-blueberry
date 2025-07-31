<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    protected $fillable = [
        'user_id', 
        'text', 
        'score'
    ];

    // Relación con el modelo User - Doctor
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id'); 
    }
}

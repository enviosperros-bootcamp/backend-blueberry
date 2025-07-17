<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DoctorSpecialty extends Model
{
    protected $table = 'doctor_specialty';

    protected $fillable = ['user_id', 'specialty_id'];
}

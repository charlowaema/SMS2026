<?php

namespace App\Models;

use App\Models\Enrollment;
use App\Models\Payment;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    
public function payments(){
    return $this->hasHany(Payment::class);
}

public function profile(){
    return $this->hasOne(Profile::class);
}

public function course(){
    return $this->belongsTo(Course::class);
}

}
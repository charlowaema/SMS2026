<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AcademicYear extends Model
{
    protected $fillable = [
        'name',
        'start_date',
        'end_date',
        'is_active'
    ];

    public function semesters()
    {
        return $this->hasMany(Semester::class);
    }

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    public function feeStructures()
    {
        return $this->hasMany(FeeStructure::class);
    }
}

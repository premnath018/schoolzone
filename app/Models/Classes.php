<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Classes extends Model
{
    protected $fillable = [
        'class_name',
        'grade_level',
        'section',
        'teacher_id',
        'academic_year_id',
        'total_students',
    ];

    // ... other methods remain the same

    public function teacher()
    {
        return $this->belongsTo(Teacher::class); // Relate to Teacher model through belongsTo relationship
    }

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class); // Relate to AcademicYear model through belongsTo relationship
    }
  
    public function students()
    {
        return $this->hasMany(Student::class, 'class_id'); // Assuming the foreign key is 'class_id'
    }
    
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    use HasFactory;

    protected $fillable = [
        'exam_name',
        'class_id',
        'subject_id',
        'exam_date',
        'duration',
        'description',
        'exam_type_code',
    ];

    // Define relationships if needed
    public function class()
    {
        return $this->belongsTo(Classes::class, 'class_id');
    }
    public function students()
    {
        return $this->hasManyThrough(Student::class, Classes::class, 'id', 'class_id');
    }
    

    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }

    public function examType()
    {
        return $this->belongsTo(ExamType::class, 'exam_type_code', 'exam_type_code');
    }
    public function marks()
    {
        return $this->hasMany(Mark::class);
    }
    public function examGroup()
    {
        return $this->hasMany(ExamMap::class);
    }


}

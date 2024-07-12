<?php

// In ExamType.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamType extends Model
{
    use HasFactory;

    protected $fillable = [
        'exam_type_code',
        'exam_type_name', // Add this line
        'description',
        'pass_mark',
        'total_mark',
    ];

    // Add relationships or other methods as needed
}

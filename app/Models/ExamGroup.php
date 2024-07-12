<?php
namespace App\Models; // Adjust namespace if needed

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamGroup extends Model
{
    use HasFactory;

    protected $table = 'exam_group';
    
    protected $fillable = [
        'exam_group_name',
        'sheet_title',
        'description',
        'grade_level',
        'exam_type_id',
    ];

    /**
     * Relationship with exam types (if applicable)
     */
    public function examType()
    {
        return $this->belongsTo(ExamType::class); // Assuming you have an ExamType model
    }

}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamMap extends Model
{
    use HasFactory;

    protected $table = 'exam_group_map';

    protected $fillable = [
        'exam_group_id',
        'exam_id',
    ];

    /**
     * Relationships
     */
    public function examGroup()
    {
        return $this->belongsTo(ExamGroup::class);
    }

    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }
}

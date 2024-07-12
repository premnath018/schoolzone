<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'students';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'middle_name',
        'date_of_birth',
        'gender',
        'address',
        'phone_number',
        'email_address',
        'grade_level',
        'class_id',
        'admission_date',
        'status',
        'emergency_contact_name',
        'emergency_contact_number',
        'medical_conditions',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'date_of_birth' => 'date',
    ];


    public function class()
    {
        return $this->belongsTo(Classes::class);
    }

    public function getDistinctGradeLevels()
    {
        return Classes::select('grade_level')->distinct()->get();
    }
    public function marks()
    {
        return $this->hasMany(Mark::class);
    }
    
}

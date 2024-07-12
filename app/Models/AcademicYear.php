<?php

namespace App\Models;
use Carbon\Carbon;

use Illuminate\Database\Eloquent\Model;

class AcademicYear extends Model
{
    protected $fillable = [
        'name',
        'start_date',
        'end_date',
    ];

    public function getNameAttribute($value)
    {
        return ucfirst(strtolower($value)); // Convert name to proper case
    }

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = strtolower($value); // Store name in lowercase for consistency
    }

    // public function classes()
    // {
    //     return $this->hasMany(Class::class); // Relate to Class model through hasMany relationship
    // }

    public function currentAcademicYear()
    {
        return self::where('start_date', '<=', Carbon::now())
            ->where('end_date', '>=', Carbon::now())
            ->first(); // Get the current academic year based on date
    }
}

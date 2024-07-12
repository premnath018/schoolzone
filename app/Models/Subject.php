<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    
protected $fillable = [
        'name',
        'code',
        'description',
        // Add other fields as needed (e.g., class_level)
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        // No casts necessary for the current fields
    ];

    // Relationships (if applicable):

    // Example: A subject might belong to a class level
}
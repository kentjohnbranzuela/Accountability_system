<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Technician extends Model
{
    use HasFactory;
    protected $fillable = [
        'Position', 'name', 'date', 'quantity', 'description', 'ser_no', 'status'
    ];
    
    // Ensure ser_no can be null
    protected $casts = [
        'ser_no' => 'string',
    ];    
}


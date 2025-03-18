<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cdo extends Model
{
    use HasFactory;

    protected $table = 'cdo_records';

    protected $fillable = [
        'position',
        'name',
        'date', // ✅ Renamed from 'date' to 'date_received'
        'quantity',
        'description',
        'ser_no',
        'status'
        
        
    ];
}


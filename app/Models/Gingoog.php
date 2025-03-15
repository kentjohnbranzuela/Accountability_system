<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gingoog extends Model
{
    use HasFactory;

    protected $table = 'gingoogs';

    protected $fillable = [
        'position',
        'name',
        'date', // ✅ Renamed from 'date' to 'date_received'
        'quantity',
        'description',
        'ser_no',
        'status',
    ];
}



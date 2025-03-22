<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TurnOver extends Model
{
    use HasFactory;
    protected $table = 'turn_overs';
    protected $fillable = [
        'position',
        'name',
        'date',
        'quantity',
        'description',
        'ser_no',
        'status'
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResignRecord extends Model
{
    use HasFactory;

    protected $table = 'resign_records';

    protected $fillable = [
        'position',
        'name',
        'date',
        'quantity',
        'description',
        'ser_no',
        'status',
    ];
}

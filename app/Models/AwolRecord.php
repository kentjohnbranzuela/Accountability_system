<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AwolRecord extends Model {
    use HasFactory;

    protected $table = 'awol_records'; // Ensure it matches your table name

    protected $fillable = [
        'position', 'name', 'date', 'quantity', 'description', 'ser_no', 'status'
    ];
}


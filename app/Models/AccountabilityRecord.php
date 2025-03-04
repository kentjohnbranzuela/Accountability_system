<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccountabilityRecord extends Model
{
    use HasFactory;

    protected $table = 'accountability_records'; // Ensure it matches your table name

    protected $fillable = [
        'id_number', 'name', 'date', 'quantity', 'description', 'ser_no', 'status'
    ];
}

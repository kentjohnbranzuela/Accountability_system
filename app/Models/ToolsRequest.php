<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ToolsRequest extends Model
{
    use HasFactory;

    protected $table = 'tools_requests';

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

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Validate extends Model
{
    use HasFactory;
    protected $table = "validates";
    protected $fillable = [
        'user_id',
        'image',
        'temp_name',
        'status'
    ];
}

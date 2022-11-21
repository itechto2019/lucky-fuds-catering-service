<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Extend extends Model
{
    use HasFactory;
    protected $table = "extends";
    protected $fillable = [
        'user_rent_id',
        'date',
        'return'
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Returns extends Model
{
    use HasFactory;
    protected $table = "returns";
    protected $fillable = [
        'user_rent_id',
        'date',
        'return',
    ];
}

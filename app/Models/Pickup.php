<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pickup extends Model
{
    use HasFactory;
    protected $table = "pickups";

    protected $fillable = [
        'user_rent_id'
    ];
}

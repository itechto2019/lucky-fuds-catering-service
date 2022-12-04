<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminInfo extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        "profile",
        "name",
        "contact",
        'temp_name',
    ];
    protected $hidden = [
        "created_at",
        "updated_at"
    ];
}

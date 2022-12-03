<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OnlineTransaction extends Model
{
    use HasFactory;
    protected $fillable = [
        'transaction',
        'user_rent',
        'payment_status',
        'reference',
        'image',
        'temp_name',
    ];
}

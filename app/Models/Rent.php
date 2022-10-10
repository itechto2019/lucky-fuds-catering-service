<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rent extends Model
{
    use HasFactory;
    protected $table = "rents";
    protected $fillable = [
        'user_id',
        'for_rent_id',
        'client',
        'amount',
        'date',
        'return',
        'status',
        'is_returned'
    ];
    public function extends()
    {
        return $this->hasOne(Extend::class);
    }
    public function returns()
    {
        return $this->hasOne(Returns::class);
    }
}

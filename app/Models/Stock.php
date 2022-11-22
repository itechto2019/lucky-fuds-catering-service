<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    use HasFactory;
    protected $table = "stocks";
    protected $fillable = [
        'image',
        'item',
        'quantity',
        'price'
    ];
    public function for_rent()
    {
        return $this->hasMany(ForRent::class);
    }
}

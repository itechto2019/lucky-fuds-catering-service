<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ForRent extends Model
{
    use HasFactory;
    protected $table = "for_rents";
    protected $fillable = [
        'stock_id',
        'quantity',
        'is_rented',
        'status'
    ];
    public function stock() {
        return $this->belongsTo(Stock::class);
    }
}

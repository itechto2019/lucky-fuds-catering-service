<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rent extends Model
{
    use HasFactory;
    protected $table = "rents";
    protected $primaryKey = "id";
    protected $guarded = [];


    public function extends() {
        return $this->hasMany(Extend::class);
    }
    public function stocks() {
        return $this->hasOne(Stock::class);
    }
    public function returns() {
        return $this->hasMany(Returns::class);
    }
    public function for_rents() {
        return $this->hasOne(ForRent::class);
    }


}

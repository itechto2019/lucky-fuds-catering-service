<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Extend extends Model
{
    use HasFactory;
    protected $table = "extends";
    protected $primaryKey = "id";
    protected $guarded = [];

    public function rents()
    {
        return $this->belongsTo(Rent::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ForRent extends Model
{
    use HasFactory;
    protected $table = "for_rents";
    protected $primaryKey = "id";
    protected $guarded = [];

    public function users()
    {
        return $this->belongsTo(User::class);
    }
    public function stock()
    {
        return $this->hasOne(Stock::class);
    }
}

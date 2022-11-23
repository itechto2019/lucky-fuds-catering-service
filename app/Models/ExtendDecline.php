<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExtendDecline extends Model
{
    use HasFactory;
    protected $table = "extend_declines";
    protected $fillable = [
        'user_rent_id'
    ];

    public function userRent() {
        return $this->belongsTo(UserRent::class, "user_rent_id");
    }
}

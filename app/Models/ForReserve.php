<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ForReserve extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_info_id',
        'user_reserve_id',
        'status'
    ];

    public function info(){
        return $this->belongsTo(UserInfo::class, "user_info_id");
    }

    public function user_reserve() {
        return $this->belongsTo(UserReserve::class, "user_reserve_id");
    }
}

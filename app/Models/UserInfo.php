<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserInfo extends Model
{
    use HasFactory;
    protected $table = "user_infos";
    protected $fillable = [
        'user_id',
        "profile",
        "name",
        "contact",
        "email",
        "address",
        "method"
    ];
    protected $hidden = [
        "created_at",
        "updated_at"
    ];
    public function user() {
        return $this->belongsTo(User::class);
    }
    // public function rent() {
    //     return $this->hasMany(UserRent::class, "user_id");
    // }

}

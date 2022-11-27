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
        'temp_name',
        'birthday',
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

}

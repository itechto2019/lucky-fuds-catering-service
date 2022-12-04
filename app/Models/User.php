<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = "users";
    protected $fillable = [
        'email',
        'password',
        'is_admin',
    ];


    protected $hidden = [
        'password',
        'remember_token',
        "created_at",
        "updated_at"
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    public function info() {
        return $this->hasOne(UserInfo::class);
    }
    public function validate() {
        return $this->hasOne(Validate::class);
    }
    public function verify() {
        return $this->hasOne(Verify::class);
    }
    public function admin_info() {
        return $this->hasOne(AdminInfo::class);
    }
    public function about() {
        return $this->hasOne(AboutInfo::class, 'user');
    }
}

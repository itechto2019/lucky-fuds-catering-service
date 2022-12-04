<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AboutInfo extends Model
{
    use HasFactory;
    protected $fillable = [
        'user',
        'body',
    ];
    public function user() {
        return $this->belongsTo(User::class,'user');
    }
}

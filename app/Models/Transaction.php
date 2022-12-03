<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_rent',
        'payment_method'
    ];
    public function online_transaction () {
        return $this->hasOne(OnlineTransaction::class, 'transaction');
    }
    public function extend_online_transaction () {
        return $this->hasOne(ExtendOnlineTransaction::class, 'transaction');
    }
    public function offline_transaction() {
        return $this->hasOne(OfflineTransaction::class, 'transaction');
    }
}

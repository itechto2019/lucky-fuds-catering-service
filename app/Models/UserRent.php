<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserRent extends Model
{
    use HasFactory;
    protected $table = "user_rents";
    protected $fillable = [
        'user_info_id',
        'stock_id',
        'for_rent_id',
        'amount',
        'address',
        'date',
        'return',
        'status',
        'is_returned'
    ];
    public function info() {
        return $this->belongsTo(UserInfo::class, "user_info_id");
    }
    public function stock() {
        return $this->belongsTo(Stock::class);
    }
    public function delivers() {
        return $this->hasOne(Deliver::class);
    }
    public function pickups() {
        return $this->hasOne(Pickup::class);
    }
    public function extends() {
        return $this->hasOne(Extend::class);
    }
    public function for_rent() {
        return $this->belongsTo(ForRent::class);
    }
    public function return() {
        return $this->hasOne(Returns::class);
    }
    
    public function rent_approve() {
        return $this->hasOne(RentApprove::class);
    }
    public function rent_decline() {
        return $this->hasOne(RentDecline::class);
    }
    public function extend_approve() {
        return $this->hasOne(ExtendApprove::class);
    }
    public function extend_decline() {
        return $this->hasOne(ExtendDecline::class);
    }
}

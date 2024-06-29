<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CouponModel extends Model
{
    
    protected $table = 'coupon';
    protected $fillable = ['admin_id', 'coupon_name', 'coupon_code', 'coupon_type', 'coupon_usage', 'coupon_price', 'start_date', 'end_date', 'is_active', 'max_discount', 'min_cart_amount', 'coupon_for'];
    protected $guarded = ['id'];

}
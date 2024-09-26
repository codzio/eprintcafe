<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PhysicalOrderModel extends Model
{
    
    protected $table = 'physical_orders';
    protected $fillable = ['order_id', 'admin_id', 'user_id', 'product_id', 'invoice_number', 'coupon_code', 'discount', 'shipping', 'packaging_charges', 'gst_charges', 'paid_amount', 'transaction_details', 'customer_address', 'qty', 'status', 'order_status', 'is_shipping_free', 'remark', 'additional_discount', 'courier', 'payment_method', 'is_deleted', 'pickup_option', 'wallet_amount'];
    protected $guarded = ['id'];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($order) {
            $yearMonth = date('Y/m');
            $latestOrderId = str_pad(OrderModel::max('id') + 1, 2, '0', STR_PAD_LEFT);
            $order->invoice_number = "EPC-PHY/{$yearMonth}/{$latestOrderId}";
        });
    }

}
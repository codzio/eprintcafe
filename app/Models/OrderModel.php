<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderModel extends Model
{
    
    protected $table = 'orders';
    protected $fillable = ['order_id', 'admin_id', 'user_id', 'product_id', 'invoice_number', 'product_name', 'product_details', 'weight_details', 'coupon_code', 'discount', 'shipping', 'paid_amount', 'price_details', 'transaction_details', 'customer_address', 'document_link', 'qty', 'no_of_copies', 'status', 'order_status', 'is_shipping_free', 'remark', 'additional_discount', 'wetransfer_link', 'courier', 'payment_method', 'is_deleted'];
    protected $guarded = ['id'];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($order) {
            $yearMonth = date('Y/m');
            $latestOrderId = str_pad(OrderModel::max('id') + 1, 2, '0', STR_PAD_LEFT);
            $order->invoice_number = "EPC/{$yearMonth}/{$latestOrderId}";
        });
    }

}
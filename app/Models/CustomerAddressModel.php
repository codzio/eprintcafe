<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerAddressModel extends Model
{
    
    protected $table = 'customer_address';
    protected $fillable = ['user_id', 'shipping_name', 'shipping_company_name', 'shipping_address','shipping_city', 'shipping_state', 'shipping_pincode', 'shipping_email', 'shipping_phone', 'is_billing_same', 'billing_name', 'billing_company_name', 'billing_address', 'billing_city', 'billing_state', 'billing_pincode', 'billing_email', 'billing_phone', 'gst_number'];
    protected $guarded = ['id'];

}
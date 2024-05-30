<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItemModel extends Model
{
    
    protected $table = 'order_items';
    protected $fillable = ['order_id', 'product_id', 'product_name', 'product_details', 'product_detail_ids', 'weight_details', 'price_details', 'qty', 'no_of_copies'];
    protected $guarded = ['id'];

}
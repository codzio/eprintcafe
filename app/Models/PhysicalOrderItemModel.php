<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PhysicalOrderItemModel extends Model
{
    
    protected $table = 'physical_order_items';
    protected $fillable = ['order_id', 'product_id', 'product_name', 'product_details', 'product_detail_ids', 'weight_details', 'price_details', 'qty', 'price'];
    protected $guarded = ['id'];

}
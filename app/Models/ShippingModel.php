<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShippingModel extends Model
{
    
    protected $table = 'shipping';
    protected $fillable = ['admin_id', 'pincode', 'free_shipping', 'under_500gm', '500_1000gm', '1000_2000gm', '2000_3000gm', 'is_active'];
    protected $guarded = ['id'];

}
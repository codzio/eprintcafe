<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductModel extends Model
{
    
    protected $table = 'product';
    protected $fillable = ['admin_id', 'name', 'slug', 'category_id', 'description', 'thumbnail_id', 'gallery_images', 'display_on_home', 'is_active', 'registered_hsn_code', 'unregistered_hsn_code', 'banner_image', 'short_description', 'button_name', 'product_type', 'mrp', 'sp'];
    protected $guarded = ['id'];

}
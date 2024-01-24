<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductModel extends Model
{
    
    protected $table = 'product';
    protected $fillable = ['admin_id', 'name', 'slug', 'category_id', 'description', 'thumbnail_id', 'gallery_images', 'is_active'];
    protected $guarded = ['id'];

}
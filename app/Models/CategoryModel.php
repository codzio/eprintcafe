<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategoryModel extends Model
{
    
    protected $table = 'category';
    protected $fillable = ['admin_id', 'category_name', 'category_slug', 'category_description', 'parent', 'category_img', 'is_active'];
    protected $guarded = ['id'];

}
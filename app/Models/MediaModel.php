<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MediaModel extends Model
{
    
    protected $table = 'media';
    protected $fillable = ['admin_id', 'original_name', 'new_name', 'ext', 'path', 'year', 'month', 'date', 'alt', 'size', 'created_at', 'updated_at'];
    protected $guarded = ['id'];

}
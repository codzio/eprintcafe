<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CoverModel extends Model
{
    
    protected $table = 'cover';
    protected $fillable = ['admin_id', 'cover', 'slug'];
    protected $guarded = ['id'];

}
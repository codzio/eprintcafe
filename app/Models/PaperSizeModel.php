<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaperSizeModel extends Model
{
    
    protected $table = 'paper_size';
    protected $fillable = ['admin_id', 'size', 'slug', 'measurement'];
    protected $guarded = ['id'];

}
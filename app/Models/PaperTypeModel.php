<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaperTypeModel extends Model
{
    
    protected $table = 'paper_type';
    protected $fillable = ['admin_id', 'paper_size_id', 'paper_type', 'paper_type_slug'];
    protected $guarded = ['id'];

}
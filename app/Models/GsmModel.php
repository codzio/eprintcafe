<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GsmModel extends Model
{
    
    protected $table = 'gsm';
    protected $fillable = ['admin_id', 'paper_size', 'gsm', 'weight', 'rate', 'paper_type', 'paper_type_price'];
    protected $guarded = ['id'];

}
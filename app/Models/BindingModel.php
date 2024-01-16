<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BindingModel extends Model
{
    
    protected $table = 'binding';
    protected $fillable = ['admin_id', 'paper_size_id', 'binding_name', 'price'];
    protected $guarded = ['id'];

}
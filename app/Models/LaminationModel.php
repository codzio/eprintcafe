<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LaminationModel extends Model
{
    
    protected $table = 'lamination';
    protected $fillable = ['admin_id', 'paper_size_id', 'lamination', 'lamination_type', 'price'];
    protected $guarded = ['id'];

}
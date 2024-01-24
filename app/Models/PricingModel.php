<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PricingModel extends Model
{
    
    protected $table = 'pricing';
    protected $fillable = ['admin_id', 'product_id', 'paper_size_id', 'paper_gsm_id', 'paper_type_id', 'side', 'color', 'other_price'];
    protected $guarded = ['id'];

}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CartModel extends Model
{
    
    protected $table = 'cart';
    protected $fillable = ['temp_id', 'user_id', 'product_id', 'paper_size_id', 'paper_gsm_id', 'paper_type_id', 'print_side', 'color', 'binding_id', 'lamination_id', 'cover_id', 'qty', 'no_of_copies', 'document_link'];
    protected $guarded = ['id'];

}
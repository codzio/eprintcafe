<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BarcodeModel extends Model
{
    
    protected $table = 'barcode';
    protected $fillable = ['admin_id', 'barcode', 'is_used', 'is_active'];
    protected $guarded = ['id'];

}
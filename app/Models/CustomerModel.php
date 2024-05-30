<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerModel extends Model
{
    
    protected $table = 'customer';
    protected $fillable = ['name', 'email', 'phone', 'address','city', 'state', 'password', 'forgot_token', 'forgot_token_validity'];
    protected $guarded = ['id'];

}
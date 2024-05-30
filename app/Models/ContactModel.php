<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactModel extends Model
{
    
    protected $table = 'contact';
    protected $fillable = ['admin_id', 'name', 'email', 'phone', 'subject', 'message'];
    protected $guarded = ['id'];

}
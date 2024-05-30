<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RolesModel extends Model
{
    
    protected $table = 'roles';
    protected $fillable = ['admin_id', 'role_name', 'permissions', 'created_at', 'updated_at'];
    protected $guarded = ['id'];

}
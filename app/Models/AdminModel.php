<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminModel extends Model
{
    protected $table = 'admins';
    protected $fillable = [ 'admin_id', 'role_id', 'name', 'email', 'password', 'profile', 'address', 'phone_number', 'two_step', 'is_active', 'otp_attempt', 'otp_block_date', 'otp_block_time', 'forgot_token', 'forgot_token_validity'];
}

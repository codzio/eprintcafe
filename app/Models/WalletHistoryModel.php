<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WalletHistoryModel extends Model
{
    
    protected $table = 'wallet_history';
    protected $fillable = ['admin_id', 'user_id', 'debit', 'credit', 'narration'];
    protected $guarded = ['id'];

}
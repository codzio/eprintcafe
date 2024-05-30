<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SettingModel extends Model
{
    
    protected $table = 'settings';
    protected $fillable = ['admin_id', 'meta_key', 'meta_value', 'created_at', 'updated_at'];
    protected $guarded = ['id'];

    public function updateSetting($keyValuePairs)
    {
        $adminId = adminId();
        foreach ($keyValuePairs as $key => $value) {
            $this->updateOrCreate(['admin_id' => $adminId, 'meta_key' => $key], ['meta_value' => $value]);
        }
    }

    public function getSetting($key)
    {
        return $this->where('meta_key', $key)->value('meta_value');
    }

}
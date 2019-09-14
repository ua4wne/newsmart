<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Option extends Model
{
    //указываем имя таблицы
    protected $table = 'options';

    protected $fillable = ['device_id','address','val','min_val','max_val','unit','alias','name','to_log'];

    public function device()
    {
        return $this->belongsTo('App\Models\Device','device_id','id');
    }

    public function rules()
    {
        return $this->hasMany('App\Models\Rule','option_id', 'id');
    }

    public function RuleCount()
    {
        $rule = $this->rules()->count('option_id');
        return $rule;
    }
}

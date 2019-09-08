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
        return $this->belongsTo('App\Models\Device','option_id','id');
    }
}

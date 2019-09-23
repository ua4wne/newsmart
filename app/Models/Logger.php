<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Logger extends Model
{
    //указываем имя таблицы
    protected $table = 'logger';

    public function option()
    {
        return $this->belongsTo('App\Models\Option','option_id','id');
    }
}

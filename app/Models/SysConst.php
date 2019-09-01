<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SysConst extends Model
{
    //указываем имя таблицы
    protected $table = 'configs';

    protected $fillable = ['param','val','descr'];
}

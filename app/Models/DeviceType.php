<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeviceType extends Model
{
    //указываем имя таблицы
    protected $table = 'device_types';

    protected $fillable = ['name','descr'];
}

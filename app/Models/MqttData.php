<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MqttData extends Model
{
    //указываем имя таблицы
    protected $table = 'mqtt_data';

    protected $fillable = ['time','topic','value'];

}

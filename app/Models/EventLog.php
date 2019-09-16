<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventLog extends Model
{
    //указываем имя таблицы
    protected $table = 'eventlogs';

    protected $fillable = ['type','msg'];
}

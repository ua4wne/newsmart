<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Protocol extends Model
{
    //указываем имя таблицы
    protected $table = 'protocols';

    protected $fillable = ['code','name'];

}

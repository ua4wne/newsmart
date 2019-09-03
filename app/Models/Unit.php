<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    //указываем имя таблицы
    protected $table = 'units';

    protected $fillable = ['name'];
}

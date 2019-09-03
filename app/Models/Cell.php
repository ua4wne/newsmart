<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cell extends Model
{
    //указываем имя таблицы
    protected $table = 'cells';

    protected $fillable = ['name'];
}

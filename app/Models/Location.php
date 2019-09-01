<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    //указываем имя таблицы
    protected $table = 'locations';

    protected $fillable = ['name','alias','is_show'];
}

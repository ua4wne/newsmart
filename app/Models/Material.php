<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    //указываем имя таблицы
    protected $table = 'materials';

    protected $fillable = ['name','category_id','image'];

    public function category()
    {
        return $this->belongsTo('App\Models\Category','category_id','id');
    }
}

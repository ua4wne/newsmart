<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tarif extends Model
{
    //указываем имя таблицы
    protected $table = 'tarifs';

    protected $fillable = ['device_id','koeff','unit'];

    public function device()
    {
        return $this->belongsTo('App\Models\Device','device_id','id');
    }
}

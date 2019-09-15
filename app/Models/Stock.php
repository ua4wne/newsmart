<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    //указываем имя таблицы
    protected $table = 'stocks';

    protected $fillable = ['cell_id','material_id','quantity','unit_id','price'];

    public function cell()
    {
        return $this->belongsTo('App\Models\Cell','cell_id','id');
    }

    public function material()
    {
        return $this->belongsTo('App\Models\Material','material_id','id');
    }

    public function unit()
    {
        return $this->belongsTo('App\Models\Unit','unit_id','id');
    }
}

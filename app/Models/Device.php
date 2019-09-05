<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    //указываем имя таблицы
    protected $table = 'devices';

    protected $fillable = ['uid','name','descr','address','verify','status','protocol_id','location_id','type_id','image'];

    public function protocol()
    {
        return $this->belongsTo('App\Models\Protocol','protocol_id','id');
    }

    public function location()
    {
        return $this->belongsTo('App\Models\Location','location_id','id');
    }

    public function type()
    {
        return $this->belongsTo('App\Models\DeviceType','type_id','id');
    }
}

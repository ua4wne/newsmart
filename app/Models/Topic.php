<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Topic extends Model
{
    //указываем имя таблицы
    protected $table = 'topics';

    protected $fillable = ['option_id','topic_id','route'];

    public function mqttdata()
    {
        return $this->belongsTo('App\Models\MqttData','topic_id','id');
    }

    public function option()
    {
        return $this->belongsTo('App\Models\Option','option_id','id');
    }
}

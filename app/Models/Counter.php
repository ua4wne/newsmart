<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Counter extends Model
{
    //указываем имя таблицы
    protected $table = 'counters';

    protected $fillable = ['device_id','_year','_month','val','delta','price'];

    public function device()
    {
        return $this->belongsTo('App\Models\Device','device_id','id');
    }

    //таблица по счетчикам
    public static function StatYear($year){
        $content='<table class="table table-striped table-bordered">
            <tr><th>Счетчик</th><th>Январь</th><th>Февраль</th><th>Март</th><th>Апрель</th><th>Май</th><th>Июнь</th><th>Июль</th><th>Август</th><th>Сентябрь</th>
                <th>Октябрь</th><th>Ноябрь</th><th>Декабрь</th>
            </tr>';
        $id = DeviceType::select('id')->where(['name'=>'Счетчик'])->first()->id;
        //выбираем все счетчики
        $models = Device::select(['id','name'])->where(['type_id'=>$id])->get();
        foreach ($models as $model){
            $data = array(1=>0,0,0,0,0,0,0,0,0,0,0,0); //показания счетчиков, нумерация с 1
            $content.='<tr><td>'.$model->name.'</td>';
            $logs = self::select(['_month','delta'])->where(['device_id'=>$model->id])->where(['_year'=>$year])->orderBy('_month', 'asc')->get();
            foreach ($logs as $key=>$log){
                $k = (int)$log->_month;
                $data[$k]=$log->delta;
            }
            foreach ($data as $val){
                $content .='<td>'.$val.'</td>';
            }
            $content .='</tr>';
        }
        $content.='</tr></table>';
        return $content;
    }
}

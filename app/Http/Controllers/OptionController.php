<?php

namespace App\Http\Controllers;

use App\Models\Device;
use App\Models\Option;
use Illuminate\Http\Request;

class OptionController extends Controller
{
    public function index($id){
        if(view()->exists('options')){
            //выбираем данные из таблицы
            $rows = Option::where(['device_id'=>$id])->get();
            $device = Device::find($id)->name;
            $head = 'Параметры устройства';
            $params = ['state'=>'Состояние','celsio'=>'Температура','humidity'=>'Влажность', 'pressure'=>'Давление',
                'light'=>'Освещенность', 'alarm'=>'Контроль', 'rssi'=>'Уровень сигнала', 'power'=>'Мощность', 'vcc'=>'Напряжение питания', 'acdc'=>'Напряжение сети','current'=>'Сила тока'];
            return view('options',[
                'title'=>'Параметры',
                'head' => $head,
                'rows' => $rows,
                'id' => $id,
                'params' => $params,
                'device' => $device,
            ]);
        }
        abort(404);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Device;
use App\Models\DeviceType;
use App\Models\Tarif;
use Illuminate\Http\Request;

class TarifController extends Controller
{
    public function index(){
        if(view()->exists('refs.tarifs')){
            //Определяем id типа устройств - счетчики
            $row = DeviceType::select('id')->where(['name'=>'Счетчик'])->first();
            //выбираем все счетчики, у которых нет тарифов
            $id = Tarif::select('device_id')->get()->toArray();
            $devs = Device::where(['type_id'=>$row->id])->whereNotIn('id', $id)->get();
            $devsel = array();
            foreach ($devs as $val){
                $devsel[$val->id] = $val->name;
            }
            //выбираем данные из таблицы
            $rows = Tarif::all();
            return view('refs.tarifs',[
                'title'=>'Тарифы',
                'head' => 'Тарифы на услуги',
                'rows' => $rows,
                'devsel' => $devsel,
            ]);
        }
        abort(404);
    }
}

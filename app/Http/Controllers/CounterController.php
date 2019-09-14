<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Lib\LibraryController;
use App\Models\Counter;
use App\Models\Device;
use App\Models\DeviceType;
use Illuminate\Http\Request;

class CounterController extends Controller
{
    public function index(){
        if(view()->exists('counters')){
            $year = date('Y');
            $month = LibraryController::GetMonths();
            $smonth = date("m");
            if(strlen($smonth)==1)
                $smonth.='0';
            //Определяем id типа устройств - счетчики
            $row = DeviceType::select('id')->where(['name'=>'Счетчик'])->first();
            //выбираем все счетчики
            $devs = Device::select('id','name')->where(['type_id'=>$row->id])->get();
            $devsel = array();
            foreach ($devs as $val){
                $devsel[$val->id] = $val->name;
            }
            //выбираем данные из таблицы
            $content = Counter::StatYear($year);
            return view('counters',[
                'title'=>'Услуги ЖКХ',
                'head' => 'Учет услуг ЖКХ за '. $year . ' год.',
                'content' => $content,
                'devsel' => $devsel,
                'month' => $month,
                'smonth' => $smonth,
                'year' => $year,
            ]);
        }
        abort(404);
    }
}

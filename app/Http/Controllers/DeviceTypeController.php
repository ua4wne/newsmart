<?php

namespace App\Http\Controllers;

use App\Models\DeviceType;
use Illuminate\Http\Request;

class DeviceTypeController extends Controller
{
    public function index(){
        if(view()->exists('refs.device_type')){
            //выбираем данные из таблицы
            $rows = DeviceType::all();
            return view('refs.device_type',[
                'title'=>'Типы устройств',
                'head' => 'Типы устройств',
                'rows' => $rows,
            ]);
        }
        abort(404);
    }
}

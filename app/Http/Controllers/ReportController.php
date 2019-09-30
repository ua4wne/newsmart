<?php

namespace App\Http\Controllers;

use App\Models\Option;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(){
        if(view()->exists('report')){
            //выбираем все параметры
            $params = Option::where('to_log','=','1')->orderBy('device_id')->get();
            $paramsel = array();
            foreach ($params as $val){
                $paramsel[$val->id] = $val->name . ' (' . $val->device->name . ' - ' . $val->device->location->name . ')';
            }
            $data = [
                'title' => 'Графики',
                'head' => 'Установка фильтрации',
                'paramsel' => $paramsel,
            ];
            return view('report', $data);
        }
        abort(404);
    }
}

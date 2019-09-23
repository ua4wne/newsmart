<?php

namespace App\Http\Controllers;

use App\Models\GetDate;
use App\Models\SysConst;
use Illuminate\Http\Request;

class RequestController extends Controller
{
    public function index(){
        if(view()->exists('requests')){
            //какой режим установлен?
            $debug = SysConst::where(['param'=>'USE_GET_DEBUG'])->first()->val;
            //выбираем данные из таблицы
            $rows = GetDate::all();
            return view('requests',[
                'title'=>'Отладчик',
                'head' => 'GET-запросы к системе',
                'rows' => $rows,
                'debug' => $debug,
            ]);
        }
        abort(404);
    }
}

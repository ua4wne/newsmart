<?php

namespace App\Http\Controllers;

use App\Models\SysConst;
use Illuminate\Http\Request;

class SysConstController extends Controller
{
    public function index(){
        if(view()->exists('config.sysconf')){
            //выбираем данные из таблицы
            $docs = SysConst::all();
            return view('config.sysconf',[
                'title'=>'Системные настройки',
                'head' => 'Системные константы',
                'docs' => $docs,
            ]);
        }
        abort(404);
    }

}

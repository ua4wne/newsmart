<?php

namespace App\Http\Controllers;

use App\Models\Protocol;
use Illuminate\Http\Request;

class ProtocolController extends Controller
{
    public function index(){
        if(view()->exists('refs.protocols')){
            //выбираем данные из таблицы
            $rows = Protocol::all();
            return view('refs.protocols',[
                'title'=>'Протоколы',
                'head' => 'Типы протоколов',
                'rows' => $rows,
            ]);
        }
        abort(404);
    }
}

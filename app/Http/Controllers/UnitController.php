<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use Illuminate\Http\Request;

class UnitController extends Controller
{
    public function index(){
        if(view()->exists('refs.units')){
            //выбираем данные из таблицы
            $rows = Unit::all();
            return view('refs.units',[
                'title'=>'Ед. измерения',
                'head' => 'Единицы измерения',
                'rows' => $rows,
            ]);
        }
        abort(404);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Option;
use App\Models\Rule;
use Illuminate\Http\Request;

class RuleController extends Controller
{
    public function index($id){
        if(view()->exists('rules')){
            //выбираем данные из таблицы
            $rows = Rule::where(['option_id'=>$id])->get();
            $option = Option::find($id)->name;
            $head = 'Правила контроля';
            return view('rules',[
                'title'=>'Правила',
                'head' => $head,
                'rows' => $rows,
                'id' => $id,
                'option' => $option,
            ]);
        }
        abort(404);
    }
}

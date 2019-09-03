<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Material;
use Illuminate\Http\Request;

class MaterialController extends Controller
{
    public function index(){
        if(view()->exists('refs.materials')){
            //выбираем данные из таблицы
            $rows = Material::all();
            //выбираем все категории
            $cats = Category::all();
            $catsel = array();
            foreach ($cats as $val){
                $catsel[$val->id] = $val->name;
            }
            return view('refs.materials',[
                'title'=>'Номенклатура',
                'head' => 'Номенклатура',
                'rows' => $rows,
                'catsel' =>$catsel,
            ]);
        }
        abort(404);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class PickingController extends Controller
{
    public function index(){
        if(view()->exists('picking')){
            //выбираем все категории
            $cats = Category::all();
            $catsel = array();
            foreach ($cats as $val){
                $catsel[$val->id] = $val->name;
            }
            $data = [
                'title' => 'Критические остатки',
                'head' => 'Установка фильтрации',
                'catsel' => $catsel,
                'count' => 3,
            ];
            return view('picking', $data);
        }
        abort(404);
    }
}

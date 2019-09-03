<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(){
        if(view()->exists('refs.categories')){
            //выбираем данные из таблицы
            $rows = Category::all();
            return view('refs.categories',[
                'title'=>'Категории',
                'head' => 'Категории материалов',
                'rows' => $rows,
            ]);
        }
        abort(404);
    }
}

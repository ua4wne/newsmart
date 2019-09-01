<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MainController extends Controller
{
    public function index(){
        if(view()->exists('main_index')){
            //выбираем свои организации
            $content='';
            return view('main_index',[
                'title'=>'Панель',
                'content' => $content,
            ]);
        }
        abort(404);
    }
}

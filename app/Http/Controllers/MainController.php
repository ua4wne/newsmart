<?php

namespace App\Http\Controllers;

use App\Console\Commands\Topics;
use Illuminate\Http\Request;

class MainController extends Controller
{
    public function index(){
        $cmd = new Topics();
        $cmd->handle();
        if(view()->exists('main_index')){
            $content='';
            return view('main_index',[
                'title'=>'Панель',
                'content' => $content,
            ]);
        }
        abort(404);
    }
}

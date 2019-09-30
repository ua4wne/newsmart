<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BillingController extends Controller
{
    public function index(){
        if(view()->exists('billing')){
            $data = [
                'title' => 'Затраты ЖКХ',
                'head' => 'Установка фильтрации',
            ];
            return view('billing', $data);
        }
        abort(404);
    }
}

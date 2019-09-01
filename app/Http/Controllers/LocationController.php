<?php

namespace App\Http\Controllers;

use App\Models\Location;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    public function index(){
        if(view()->exists('refs.locations')){
            //выбираем данные из таблицы
            $rows = Location::all();
            return view('refs.locations',[
                'title'=>'Локации',
                'head' => 'Локации',
                'rows' => $rows,
            ]);
        }
        abort(404);
    }
}

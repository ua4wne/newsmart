<?php

namespace App\Http\Controllers;

use App\Models\Cell;
use App\Models\Stock;
use App\Models\Unit;
use Illuminate\Http\Request;

class StockController extends Controller
{
    public function index(){
        if(view()->exists('stock')){
            $cells = Cell::select('id','name')->get();
            $celsel = array();
            foreach ($cells as $val){
                $celsel[$val->id] = $val->name;
            }
            $units = Unit::select('id','name')->get();
            $usel = array();
            foreach ($units as $val){
                $usel[$val->id] = $val->name;
            }
            //выбираем данные из таблицы
            $rows = Stock::all();
            return view('stock',[
                'title'=>'Мой склад',
                'head' => 'Наличие на складе',
                'rows' => $rows,
                'celsel' => $celsel,
                'usel' => $usel,
            ]);
        }
        abort(404);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Cell;
use Illuminate\Http\Request;

class CellController extends Controller
{
    public function index(){
        if(view()->exists('refs.cells')){
            //выбираем данные из таблицы
            $rows = Cell::all();
            return view('refs.cells',[
                'title'=>'Ячейки',
                'head' => 'Места хранения',
                'rows' => $rows,
            ]);
        }
        abort(404);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\EventLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EventLogController extends Controller
{
    public function index(Request $request){
        if($request->isMethod('post')){
            $id = $request->input('id');
            if($id=='delete')
                DB::delete("delete from eventlogs where stat='1'");
            return 'OK';
        }
        if(view()->exists('eventlogs')){
            //выбираем данные из таблицы
            $rows = EventLog::all();
            return view('eventlogs',[
                'title'=>'Журнал событий',
                'head' => 'Журнал событий',
                'rows' => $rows,
            ]);
        }
        abort(404);
    }
}

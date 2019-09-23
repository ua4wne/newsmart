<?php

namespace App\Http\Controllers\Ajax;

use App\Models\EventLog;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class EventLogController extends Controller
{
    public function read(Request $request){
        if($request->isMethod('post')){
            $id = $request->input('id');
            $model = EventLog::where(['id'=>$id])->first();
            $model->stat = '1';
            if($model->save())
                return 'OK';
            else
                return 'ERR';
        }
    }
}

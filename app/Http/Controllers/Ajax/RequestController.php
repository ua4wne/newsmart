<?php

namespace App\Http\Controllers\Ajax;

use App\Models\SysConst;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class RequestController extends Controller
{
    public function delete(Request $request){
        if($request->isMethod('post')){
            $id = $request->input('id');
            if($id=='delete')
                DB::table('requests')->delete();
            return 'OK';
        }
    }

    public function debug(Request $request){
        if($request->isMethod('post')){
            $id = $request->input('id');
            if($id=='on'){
                $model = SysConst::where(['param'=>'USE_GET_DEBUG'])->first();
                $model->val = 'true';
            }
            if($id=='off'){
                $model = SysConst::where(['param'=>'USE_GET_DEBUG'])->first();
                $model->val = 'false';
            }
            if($model->save())
                return 'OK';
            else
                return 'ERR';
        }
    }
}

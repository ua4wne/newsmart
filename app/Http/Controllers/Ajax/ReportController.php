<?php

namespace App\Http\Controllers\Ajax;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function read(Request $request)
    {
        if ($request->isMethod('post')) {
            $data = array();
            $input = $request->except('_token'); //параметр _token нам не нужен
            $from = $input['from'];
            $to = $input['to'];
            $param = $input['param'];
            if(!empty($from) && !empty($to) && !empty($param)){
                $f = Carbon::parse($from);
                $t = Carbon::parse($to);
                $tt = $t->diffInHours($f);

                if($tt <= 72)
                    $rows = DB::select("SELECT ROUND(AVG(val),2) AS val, updated_at AS dt FROM logger WHERE option_id=$param and updated_at BETWEEN '$from' AND '$to' GROUP BY dt");
                else
                    $rows = DB::select("SELECT ROUND(AVG(val),2) AS val, SUBSTRING(updated_at,1,10) AS dt FROM logger WHERE option_id=$param and updated_at BETWEEN '$from' AND '$to' GROUP BY dt");
                $k=0;
                foreach ($rows as $in) {
                    $val = array();
                    $val['date'] = $in->dt;
                    $val['value'] = $in->val;
                    array_push($data, $val);
                }
                return json_encode($data);
            }
        }
    }
}

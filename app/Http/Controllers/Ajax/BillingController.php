<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\Lib\LibraryController;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class BillingController extends Controller
{
    public function pie(Request $request)
    {
        if ($request->isMethod('post')) {
            $data = array();
            $input = $request->except('_token'); //параметр _token нам не нужен
            $period = $input['period'];
            if($period=='all'){
                $now = DB::select("select d.name as name, sum(l.price) as price from counters l inner join devices d on d.id=l.device_id group by name");
            }
            else{
                $year=$input['year'];
                if(empty($year)){
                    $year = date('Y');
                }
                $now = DB::select("select d.name as name, sum(l.price) as price from counters l inner join devices d on d.id=l.device_id where _year='$year' group by name");
            }
            foreach ($now as $in) {
                $val = array();
                $val['name'] = $in->name;
                $val['price'] = $in->price;
                array_push($data, $val);
            }
            return json_encode($data);
        }
    }

    public function bar(Request $request)
    {
        if ($request->isMethod('post')) {
            $data = array();
            $input = $request->except('_token'); //параметр _token нам не нужен
            $period = $input['period'];
            if($period=='all'){
                $now = DB::select("select SUM(price) AS price, _year as period from counters group by _year");
                $k=0;
                foreach($now as $in){
                    $data[$k]['y']=$in->period;
                    $data[$k]['p']=$in->price;
                    $k++;
                }
                return json_encode($data);
            }
            else{
                $year=$input['year'];
                if(empty($year)){
                    $year = date('Y');
                }
                $now = DB::select("select SUM(price) AS price, _month as period from counters where _year = '$year' group by _month");
            }
            $k=0;
            foreach($now as $in){
                $data[$k]['y']=LibraryController::SetMonth($in->period);
                $data[$k]['p']=$in->price;
                $k++;
            }
            return json_encode($data);
        }
    }
}

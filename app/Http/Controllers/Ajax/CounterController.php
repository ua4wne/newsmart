<?php

namespace App\Http\Controllers\Ajax;

use App\Models\Counter;
use App\Models\Tarif;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use Illuminate\Support\Facades\DB;

class CounterController extends Controller
{
    const NOT_VAL = 0; //нет значений
    const MORE_VAL = 1; //предыдущее значение больше текущего
    const LESS_VAL = 2; //предыдущее значение меньше текущего
    private $previous; //предыдущее показание счетчика

    public function create(Request $request)
    {
        if ($request->isMethod('post')) {
            $messages = [
                'required' => 'Поле :attribute обязательно к заполнению',
                'numeric' => 'Поле :attribute должно иметь числовое или дробное значение',
                'string' => 'Поле :attribute должно иметь строковое значение',
            ];
            $validator = Validator::make($request->all(), [
                'device_id' => 'required|numeric',
                'val' => 'required|numeric',
                '_year' => 'required|string|max:4',
                '_month' => 'required|string|max:2',
            ], $messages);
            if ($validator->passes()) {
                $input = $request->except('_token'); //параметр _token нам не нужен
                $input['created_at'] = date('Y-m-d H:i:s');
                $result = $this->CheckCountVal($input['device_id'], $input['val'], $input['_year'], $input['_month']);
                if ($result === self::MORE_VAL)
                    return 'MORE_VAL';
                $model = new Counter();
                //удаляем, если имеется запись за текущий месяц, чтобы не было дублей
                Counter::where(['device_id' => $input['device_id'], '_year' => $input['_year'], '_month' => $input['_month']])->delete();
                if ($result === self::LESS_VAL)
                    $input['delta'] = $input['val'] - $this->previous;
                else
                    $input['delta'] = $input['val']; //первая запись или замена счетчика
                $koeff = Tarif::where(['device_id' => $input['device_id']])->first()->koeff;
                $input['price'] = $input['delta'] * $koeff;
                $model->fill($input);
                if ($model->save()) {
                    return 'OK';
                }
                return 'ERR';
            }
            return response()->json(['error' => $validator->errors()->all()]);
        }
    }

    //проверка корректности данных счетчика
    private function CheckCountVal($id, $val, $year, $month)
    {
        $period = explode('-', date('Y-m', strtotime("$year-$month-01 -1 month"))); //определяем предыдущий период
        $y = $period[0];
        $m = $period[1];
        //выбираем данные за предыдущий период
        $numrow = Counter::where(['device_id' => $id, '_year' => $y, '_month' => $m])->get()->count();
        if ($numrow) {
            $row = Counter::where(['device_id' => $id, '_year' => $y, '_month' => $m])->first()->get();
            $this->previous = $row[0]['val'];
            if ($this->previous > $val)
                return self::MORE_VAL;
            else
                return self::LESS_VAL;
        } else return self::NOT_VAL;
    }

    public function counter_graph(Request $request)
    {
        if ($request->isMethod('post')) {
            $id = $request->input('id');
            $year = date('Y');
            $data=array();
            if($id=='graph_pie'){
                $now = DB::select("select d.name as name, sum(l.price) as price from counters l inner join devices d on d.id=l.device_id where _year='$year' group by name");
                foreach($now as $in){
                    $val = array();
                    $val['name'] = $in->name;
                    $val['price'] = $in->price;
                    array_push($data,$val);
                }
                return json_encode($data);
            }
            if($id=='graph_bar') {
                $old = (int)$year - 1;
                for($i=0;$i<12;$i++){
                    $data[$i]=['x'=>$i+1,'y'=>0,'z'=>0];
                }
                $now = DB::select("select SUM(price) AS price, _month as period from counters where _year = '$year' group by _month");
                $k=0;
                foreach($now as $in){
                    $data[$k]['x']=$in->period;
                    $data[$k]['y']=$in->price;
                    $k++;
                }
                $prev = DB::select("select SUM(price) AS price, _month as period from counters where _year = '$old' group by _month");
                $k=0;
                foreach($prev as $out){
                    $data[$k]['z']=$out->price;
                    $k++;
                }
                return json_encode($data);
            }
        }
    }
}

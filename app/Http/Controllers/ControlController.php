<?php

namespace App\Http\Controllers;

use App\Events\AddEventLogs;
use App\Http\Controllers\Lib\LibraryController;
use App\Models\Device;
use App\Models\GetDate;
use App\Models\Option;
use App\Models\Rule;
use App\Models\SysConst;
use Illuminate\Http\Request;

class ControlController extends Controller
{
    //http://newsmart/control?device=70ed8b0cc057d9dd&celsio[402552162517723541]=10.10&celsio[4025522324177235247]=50.10
    public function index(Request $request) //http://smart/control?device=70ed8b0cc057d9dd&celsio[one]=14.50&celsio[two]=51.00$addr[one]=402552162517723541$addr[two]=4025522324177235247
    {
        if($request->isMethod('get')){
            //какой режим установлен?
            $debug = SysConst::where(['param'=>'USE_GET_DEBUG'])->first()->val;
            if($debug=='true'){
                $log = new GetDate();
                $log->created_at = date('Y-m-d H:i:s');
                $log->request = $request->getRequestUri();
                $log->from = LibraryController::GetRealIp();
                $log->save();
            }
            $uid = $request['device']; //выделяем UID устройства из запроса и смотрим, есть ли такой в базе
            $device = Device::where(['uid'=>$uid])->first();
            if(!empty($device)){ //есть такое устройство, парсим дальше, выбираем остальные параметры
                if($device->status == '1') { //если устройство активно
                    $params = $request->query->all();
                    foreach($params as $key=>$value){
                        if($key!='device'){
                            if(is_array($value)){ //если массив параметров
                                //dd($value);
                                foreach($value as $k=>$param){
                                    $this->CheckParam($device,$key,$param,$k);
                                }
                            }
                            else {
                                $this->CheckParam($device,$key,$value);
                            }

                        }
                    }
                }
            }
        }
        if(view()->exists('empty')){
            return view('empty');
        }
        //abort(404);
    }

    private function CheckParam(Device $device,$key,$value,$address=null){
        if(empty($address))
            $option = Option::where(['device_id'=>$device->id,'alias'=>$key])->first();
        else
            $option = Option::where(['device_id'=>$device->id,'address'=>$address,'alias'=>$key])->first();
        //dd($option);
        if(empty($option)) return; //если не найден объект - выход
        $oldval = $option->val;
        if($oldval != $value){
            $option->val = $value;
            $option->save();
            //проверяем на вхождение в диапазон min - max
            if($value < $option->min_val){
                //запись в лог
                $msg = 'Значение параметра <strong>'. $option->name . ' (' . $option->device->name . ')</strong>  меньше минимально возможного! <span class="red">value=' . $value . ' min_value=' . $option->min_val . '</span>';
                event(new AddEventLogs('error',$msg));
            }
            if($value > $option->max_val){
                //запись в лог
                $msg = 'Значение параметра <strong>'. $option->name . ' (' . $option->device->name . ')</strong>  больше максимально возможного! <span class="red">value=' . $value . ' max_value=' . $option->max_val . '</span>';
                event(new AddEventLogs('error',$msg));
            }
            //ищем связанные правила
            $rcount = Rule::where(['option_id'=>$option->id])->count();
            if($rcount) {
                $location = $device->location->name;
                LibraryController::CheckRules($option,$location); //проверяем связанные правила
            }
        }
    }
}

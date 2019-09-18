<?php

namespace App\Http\Controllers\Ajax;

use App\Events\AddEventLogs;
use App\Models\MqttData;
use App\Models\Option;
use App\Models\SysConst;
use App\Models\Topic;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;

class MqttController extends Controller
{
    public function connect(Request $request)
    {
        if ($request->isMethod('post')) {
            $messages = [
                'required' => 'Поле :attribute обязательно к заполнению',
                'numeric' => 'Поле :attribute должно иметь числовое или дробное значение',
                'string' => 'Поле :attribute должно иметь строковое значение',
                'ip' => 'Поле :attribute должно быть корректным IP-адресом',
            ];
            $validator = Validator::make($request->all(), [
                'server' => 'required|ip',
                'port' => 'required|numeric',
                'login' => 'required|string|max:15',
                'pass' => 'required|string|max:15',
            ], $messages);
            if ($validator->passes()) {
                $res = 0;
                $input = $request->except('_token'); //параметр _token нам не нужен
                //определяем наличие записи
                $s = SysConst::where(['param'=>'MQTT_SERVER'])->first();
                if(empty($s)){
                    $s = new SysConst();
                    $s->param = 'MQTT_SERVER';
                    $input['created_at'] = date('Y-m-d H:i:s');
                    $s->created_at = $input['created_at'];
                }
                $s->val = $input['server'];
                $s->descr = 'Сервер MQTT Mosquitto';
                if($s->save()) $res++;
                //определяем наличие записи
                $p = SysConst::where(['param'=>'MQTT_PORT'])->first();
                if(empty($p)){
                    $p = new SysConst();
                    $p->param = 'MQTT_PORT';
                    $input['created_at'] = date('Y-m-d H:i:s');
                    $p->created_at = $input['created_at'];
                }
                $p->val = $input['port'];
                $p->descr = 'Порт сервера MQTT Mosquitto';
                if($p->save()) $res++;
                //определяем наличие записи
                $l = SysConst::where(['param'=>'MQTT_LOGIN'])->first();
                if(empty($l)){
                    $l = new SysConst();
                    $l->param = 'MQTT_LOGIN';
                    $input['created_at'] = date('Y-m-d H:i:s');
                    $l->created_at = $input['created_at'];
                }
                $l->val = $input['login'];
                $l->descr = 'Логин для сервера MQTT Mosquitto';
                if($l->save()) $res++;
                //определяем наличие записи
                $pw = SysConst::where(['param'=>'MQTT_PASSWORD'])->first();
                if(empty($pw)){
                    $pw = new Config();
                    $pw->param = 'MQTT_PASSWORD';
                    $input['created_at'] = date('Y-m-d H:i:s');
                    $pw->created_at = $input['created_at'];
                }
                $pw->val = $input['pass'];
                $pw->descr = 'Пароль для сервера MQTT Mosquitto';
                if($pw->save()) $res++;
                if ($res==4) {
                    $msg = 'Обновлены настройки подключения к серверу MQTT';
                    event(new AddEventLogs('info',$msg));
                    return 'OK';
                }
                return 'ERR';
            }
            return response()->json(['error' => $validator->errors()->all()]);
        }
    }

    public function add_topic(Request $request) {
        if ($request->isMethod('post')) {
            $messages = [
                'required' => 'Поле :attribute обязательно к заполнению',
                'numeric' => 'Поле :attribute должно иметь числовое или дробное значение',
                'string' => 'Поле :attribute должно иметь строковое значение',
            ];
            $validator = Validator::make($request->all(), [
                'topic_id' => 'required|numeric',
                'option_id' => 'required|numeric',
                'route' => 'required|string|max:10',
            ], $messages);
            if ($validator->passes()) {
                $input = $request->except('_token'); //параметр _token нам не нужен
                $dbl = Topic::where(['topic_id'=>$input['topic_id'],'route'=>$input['route']])->first();
                if(empty($dbl)){
                    $model = new Topic();
                    $input['created_at'] = date('Y-m-d H:i:s');
                    $model->fill($input);
                    if($model->save())
                        return $model->id;
                }
                else{
                    return 'DBL';
                }
            }
        }
    }

    public function newmsg(Request $request){
        if ($request->isMethod('post')) {
            $input = $request->except('_token'); //параметр _token нам не нужен
            $data = MqttData::find(['topic'=>$input['name']]);
            $topic = Topic::find(['topic_id'=>$data->id]);
            if(!empty($topic)){
                //есть такой топик, обновляем связанный параметр
                $option = Option::find($topic->option_id);
                if(!empty($option)){
                    $option->val = $input['payload'];
                    $option->save();
                }
                return 'OK';
            }
            else{
                return 'NO';
            }
        }
    }

    public function delete(Request $request){
        if ($request->isMethod('post')) {
            $input = $request->except('_token'); //параметр _token нам не нужен
            $id=$input['id'];
            return $id;
            if (($model = Topic::find($id)) !== null){
                $model->delete();
                return 'OK';
            }
        }
    }
}

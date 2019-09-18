<?php

namespace App\Http\Controllers;

use App\Models\MqttData;
use App\Models\Option;
use App\Models\SysConst;
use App\Models\Topic;
use Illuminate\Http\Request;
use Validator;

class MqttController extends Controller
{
    public function index(){
        //определяем наличие констант для подключения к серверу MQTT
        $server = SysConst::where(['param'=>'MQTT_SERVER'])->first()->val;
        $port = SysConst::where(['param'=>'MQTT_PORT'])->first()->val;
        $login = SysConst::where(['param'=>'MQTT_LOGIN'])->first()->val;
        $pass = SysConst::where(['param'=>'MQTT_PASSWORD'])->first()->val;
        $subquery = Topic::select('option_id')->get();
        $options = Option::select(['id','device_id', 'name'])->whereNotIn('id', $subquery)->get(); //выбираем только то, что еще не связано
        $selopt = array();
        foreach ($options as $option){
            $selopt[$option->id] = $option->device->name.' ('.$option->name.') - '.$option->device->location->name;
        }
        $subquery = Topic::select('topic_id')->get();
        $topics = MqttData::select(['id','topic'])->whereNotIn('id', $subquery)->get(); //выбираем только то, что еще не связано
        $seltop = array();
        foreach ($topics as $val){
            $seltop[$val->id] = $val->topic;
        }
        //выбираем сохраненные publish-топики
        $pubs = Topic::select(['id','topic_id'])->where(['route'=>'public'])->get();
        $public = '';
        foreach ($pubs as $pub){
            $pub_top = MqttData::find($pub->topic_id)->topic;
            $public .= '<li class="pub" id="' . $pub->id . '"><pre>' . $pub_top . '<i class="fa fa-trash pubs pull-right" aria-hidden="true"></i></pre></li>';
        }

        //выбираем сохраненные subscribe-топики
        $subs = Topic::select(['id','topic_id'])->where(['route'=>'subscribe'])->get();
        $subscribe = '';
        foreach ($subs as $sub){
            $sub_top = MqttData::find($sub->topic_id)->topic;
            $subscribe .= '<li class="sub" id="' . $sub->id . '"><pre>' . $sub_top . '<i class="fa fa-trash subs pull-right" aria-hidden="true"></i></pre></li>';
        }
        if(view()->exists('mqtt')){
            $data = [
                'title' => 'Монитор MQTT',
                'head' => 'Настройка работы по протоколу MQTT',
                'server' => $server,
                'port' => $port,
                'login' => $login,
                'pass' => $pass,
                'selopt' => $selopt,
                'seltop' => $seltop,
                'public' => $public,
                'subscribe' => $subscribe,
            ];
            return view('mqtt', $data);
        }
        abort(404);
    }
}

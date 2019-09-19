<?php

namespace App\Http\Controllers\Lib;

use App\Events\AddEventLogs;
use App\Models\Option;
use App\Models\Rule;
use App\Models\Sms;
use App\Models\SysConst;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;

class LibraryController extends Controller
{
    //определение реального IP юзера
    public static function GetRealIp()
    {
        if (!empty($_SERVER['HTTP_CLIENT_IP']))
        {
            $ip=$_SERVER['HTTP_CLIENT_IP'];
        }
        elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
        {
            $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
        }
        else
        {
            $ip=$_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }

    //геолокация
    public static function GeoLocation() {
        $ip = getenv('SERVER_ADDR');
        $url = 'http://api.sypexgeo.net/xml/'. $ip .'';
        $xml = simplexml_load_string(file_get_contents($url));
        $loc_array = array($xml->ip->city->lat,$xml->ip->city->lon);
        return $loc_array;
    }

    //выборка всех месяцев
    public static function GetMonths(){
        return array('01'=>'Январь','02'=>'Февраль','03'=>'Март','04'=>'Апрель','05'=>'Май','06'=>'Июнь','07'=>'Июль',
            '08'=>'Август','09'=>'Сентябрь','10'=>'Октябрь','11'=>'Ноябрь','12'=>'Декабрь',);
    }

    //возвращаем название месяца по номеру
    public static function SetMonth($id){
        $months = self::GetMonths();
        foreach ($months as $key=>$month){
            if($key == $id)
                return mb_strtolower($month,'UTF-8');
        }
    }

    public static function CheckRules(Option $model, $location){
        $time_stamp = strtotime(date('Y-m-d H:i:s')); //получаем текущую метку времени
        //определяем получателей почты
        $resp = SysConst::where(['param'=>'CONTROL_E_MAIL'])->first()->val;
        $resipients = explode(",",$resp);
        $ph = SysConst::where(['param'=>'CONTROL_PHONE'])->first()->val;
        $phones = explode(",",$ph);
        //ищем связанные правила
        $rules = Rule::where(['option_id'=>$model->id])->get();
        foreach ($rules as $rule){
            if(!empty($rule->runtime))
                $runtime = strtotime($rule->runtime); //получаем метку времени старта правила
            if($time_stamp < $runtime) continue; //время еще не вышло, пропуск правила
            if(($model->val > $rule->val) && $rule->condition == 'more'){
                if($rule->action == 'mail'){
                    $device = $model->device->name;
                    foreach ($resipients as $resipient){
                        $msg = str_replace("#LOCATION#",$location,$rule->text);
                        $msg = str_replace("#VAL#",$model->val . $model->unit,$msg);
                        self::SendMail($resipient, $msg,$device,$location);
                    }
                }
                if($rule->action == 'sms'){
                    foreach ($phones as $phone){
                        $msg = str_replace('#LOCATION#',$location,$rule->text);
                        $msg = str_replace('#VAL#',$model->val . $model->unit,$msg);
                        self::SendSms($phone,$msg);
                    }
                }
                if($rule->action == 'cmd'){
                    $cmd = $rule->text;
                    $id_rule = $rule->id;
                    self::RunCmd($cmd,$id_rule);
                }
                $runtime = $time_stamp + $rule->step;
                $rule->runtime = date( 'Y-m-d H:i:s' , $runtime);
                $rule->save();
            }
            elseif(($model->val < $rule->val) && $rule->condition == 'less'){
                if($rule->action == 'mail'){
                    foreach ($resipients as $resipient){
                        $msg = str_replace("#LOCATION#",$location,$rule->text);
                        $msg = str_replace("#VAL#",$model->val . $model->unit,$msg);
                        self::SendMail($resipient, $msg);
                    }
                }
                if($rule->action == 'sms'){
                    foreach ($phones as $phone){
                        $msg = str_replace('#LOCATION#',$location,$rule->text);
                        $msg = str_replace('#VAL#',$model->val . $model->unit,$msg);
                        self::SendSms($phone,$msg);
                    }
                }
                if($rule->action == 'cmd'){
                    $cmd = $rule->text;
                    $id_rule = $rule->id;
                    self::RunCmd($cmd,$id_rule);
                }
                $runtime = $time_stamp + $rule->step;
                $rule->runtime = date( 'Y-m-d H:i:s' , $runtime);
                $rule->save();
            }
            elseif(($rule->val == $model->val) && $rule->condition == 'equ'){
                if($rule->action == 'mail'){
                    foreach ($resipients as $resipient){
                        $msg = str_replace("#LOCATION#",$location,$rule->text);
                        $msg = str_replace("#VAL#",$model->val . $model->unit,$msg);
                        self::SendMail($resipient, $msg);
                    }
                }
                if($rule->action == 'sms'){
                    foreach ($phones as $phone){
                        $msg = str_replace('#LOCATION#',$location,$rule->text);
                        $msg = str_replace('#VAL#',$model->val . $model->unit,$msg);
                        self::SendSms($phone,$msg);
                    }
                }
                if($rule->action == 'cmd'){
                    $cmd = $rule->text;
                    $id_rule = $rule->id;
                    self::RunCmd($cmd,$id_rule);
                }
                $runtime = $time_stamp + $rule->step;
                $rule->runtime = date( 'Y-m-d H:i:s' , $runtime);
                $rule->save();
            }
            elseif(($rule->val != $model->val) && $rule->condition == 'not'){
                if($rule->action == 'mail'){
                    foreach ($resipients as $resipient){
                        $msg = str_replace("#LOCATION#",$location,$rule->text);
                        $msg = str_replace("#VAL#",$model->val . $model->unit,$msg);
                        self::SendMail($resipient, $msg);
                    }
                }
                if($rule->action == 'sms'){
                    foreach ($phones as $phone){
                        $msg = str_replace('#LOCATION#',$location,$rule->text);
                        $msg = str_replace('#VAL#',$model->val . $model->unit,$msg);
                        self::SendSms($phone,$msg);
                    }
                }
                if($rule->action == 'cmd'){
                    $cmd = $rule->text;
                    $id_rule = $rule->id;
                    self::RunCmd($cmd,$id_rule);
                }
                $runtime = $time_stamp + $rule->step;
                $rule->runtime = date( 'Y-m-d H:i:s' , $runtime);
                $rule->save();
            }
        }
    }

    public static function SendMail($to,$msg,$device,$location){
        $result = Mail::send('emails.event', array('device'=>$device,'location'=>$location,'msg'=>$msg), function($message) use ($to)
        {
            $message->to($to)->subject('Сообщение системы');
        });
        //запись в лог
        if($result){
            $msg = 'Сообщение получателю '. $to .' отправлено!';
            event(new AddEventLogs('mail',$msg));
        }
        else{
            $msg = 'Возникла ошибка при отправке системного сообщения адресату <strong>'. $to .'</strong>';
            event(new AddEventLogs('error',$msg));
        }
    }

    public static function SendSms($to,$msg){
        $model = new Sms();
        $model->phone = $to;
        $model->message = $msg;
        $cost = $model->GetCost();
        /*if($cost>0){
            self::SendMail($to,$msg);
        }*/
        $model->SendSms();
        //запись в лог
        $msg = 'На номер <strong>'. $to .'</strong> было отправлено СМС. Стоимость отправки - ' . $cost . ' руб.';
        event(new AddEventLogs('info',$msg));
    }

    public static function RunCmd($cmd,$rule){
        //запись в лог
        $msg = 'Запуск команды <strong>'. $cmd . '</strong> <a href="/main/rule/'.$rule.'">по правилу</a>';
        event(new AddEventLogs('info',$msg));
    }
}

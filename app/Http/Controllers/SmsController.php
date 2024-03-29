<?php

namespace App\Http\Controllers;

use App\Models\Sms;
use App\Models\SysConst;
use Illuminate\Http\Request;
use Validator;

class SmsController extends Controller
{
    public function index(Request $request){
        if($request->isMethod('post')){
            $input = $request->except('_token'); //параметр _token нам не нужен
            $messages = [
                'required' => 'Поле :attribute обязательно к заполнению',
                'string' => 'Поле :attribute должно быть строкой',
            ];
            $validator = Validator::make($input,[
                'phone' => 'required|string|max:12',
                'message' => 'required|string|max:255',
            ],$messages);
            if($validator->fails()){
                return redirect()->route('sms')->withErrors($validator)->withInput();
            }
            $model = new Sms();
            $model->phone = '7' . str_replace('-','',$input['phone']);
            $model->message = $input['message'];
            $cost = $model->GetCost();
                if($cost>0){
                    $msg = 'Лимит бесплатных смс на сегодня исчерпан! Стоимость отправки - '.$cost.' руб.';
                    return redirect('sms')->with('status',$msg);
                    //$model->SendViaMail();
                }
                $model->SendSms();
            //сбросили значения
            //$model->phone = User::findOne($uid)->phone;
            $model->message = '';

        }
        if(view()->exists('sms')){
            $data = [
                'title' => 'Отправка СМС',
                'head' => 'Отправка СМС',
            ];
            return view('sms', $data);
        }
        abort(404);
    }
}

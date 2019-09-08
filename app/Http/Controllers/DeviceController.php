<?php

namespace App\Http\Controllers;

use App\Models\Device;
use App\Models\DeviceType;
use App\Models\Location;
use App\Models\Protocol;
use Illuminate\Http\Request;
use Validator;

class DeviceController extends Controller
{
    public function index(){
        if(view()->exists('config.devices')){
            //выбираем данные из таблицы
            $rows = Device::all();
            return view('config.devices',[
                'title'=>'Оборудование',
                'head' => 'Список устройств',
                'rows' => $rows,
            ]);
        }
        abort(404);
    }

    public function create(Request $request){
        if($request->isMethod('post')){
            $input = $request->except('_token'); //параметр _token нам не нужен
            $messages = [
                'required' => 'Поле :attribute обязательно к заполнению',
                'integer' => 'Поле :attribute должно иметь цифровое значение',
                'mimes' => 'К загрузке допускаются только файлы jpeg,png и bmp',
                'in' => 'Поле :attribute должно иметь значение 0 или 1',
            ];
            $validator = Validator::make($input,[
                'name' => 'required|max:70',
                'address' => 'max:32',
                'verify' => 'required|in:0,1',
                'status' => 'required|in:0,1',
                'type_id' => 'required|integer',
                'protocol_id' => 'required|integer',
                'location_id' => 'required|integer',
                'image' => 'mimes:jpeg,bmp,png',
            ],$messages);
            if($validator->fails()){
                return redirect()->route('deviceAdd')->withErrors($validator)->withInput();
            }
            if($request->hasFile('image')){
                $file = $request->file('image'); //загружаем файл
                //создаем произвольное имя файла во избежание проблем с кодировками!
                $fname = substr_replace(sha1(microtime(true)), '', 12);
                $input['image'] = '/images/gallery/' . $fname.'.'.$file->getClientOriginalExtension();
                $file->move(public_path().'/images/gallery', $input['image']);
            }
            $model = new Device();
            $model->fill($input);
            $sid = md5(uniqid());
            $model->uid = substr($sid,0,16);
            $model->created_at = date('Y-m-d H:i:s');
            if($model->save()){
                $msg = 'Устройство '. $input['name'] .' добавлено!';
                return redirect('devices')->with('status',$msg);
            }
        }
        if(view()->exists('config.deviceAdd')){
            //выбираем все протоколы
            $prots = Protocol::all();
            $protsel = array();
            foreach ($prots as $val){
                $protsel[$val->id] = $val->name;
            }
            //выбираем все локации
            $locs = Location::all();
            $locsel = array();
            foreach ($locs as $val){
                $locsel[$val->id] = $val->name;
            }
            //выбираем все типы устройств
            $types = DeviceType::all();
            $typesel = array();
            foreach ($types as $val){
                $typesel[$val->id] = $val->name;
            }
            $data = [
                'title' => 'Новое устройство',
                'protsel' =>$protsel,
                'locsel' =>$locsel,
                'typesel' =>$typesel,
            ];
            return view('config.deviceAdd', $data);
        }
        abort(404);
    }

    public function edit(Request $request,$id=null){
        $model = Device::find($id);
        if($request->isMethod('post')){
            $input = $request->except('_token'); //параметр _token нам не нужен
            $messages = [
                'required' => 'Поле :attribute обязательно к заполнению',
                'integer' => 'Поле :attribute должно иметь цифровое значение',
                'mimes' => 'К загрузке допускаются только файлы jpeg,png и bmp',
                'in' => 'Поле :attribute должно иметь значение 0 или 1',
                'unique' => 'Поле :attribute должно быть уникальным',
            ];
            $validator = Validator::make($input,[
                'uid' => 'required|unique:devices|max:16',
                'name' => 'required|max:70',
                'address' => 'max:32',
                'verify' => 'required|in:0,1',
                'status' => 'required|in:0,1',
                'type_id' => 'required|integer',
                'protocol_id' => 'required|integer',
                'location_id' => 'required|integer',
                'image' => 'mimes:jpeg,bmp,png',
            ],$messages);
            if($validator->fails()){
                return redirect()->route('deviceEdit')->withErrors($validator)->withInput();
            }
            if($request->hasFile('image')){
                $file = $request->file('image'); //загружаем файл
                //создаем произвольное имя файла во избежание проблем с кодировками!
                $fname = substr_replace(sha1(microtime(true)), '', 12);
                $input['image'] = '/images/gallery/' . $fname.'.'.$file->getClientOriginalExtension();
                $file->move(public_path().'/images/gallery/', $input['image']);
            }
            else{
                $input['image'] = $input['old_image'];
            }
            if($input['image'] != $input['old_image']){ //загрузили новый файл, старый нужно удалить
                if(($input['old_image']!='/images/noimage.jpg') && !empty($input['old_image']))
                    unlink(public_path().$input['old_image']);
            }
            unset($input['old_image']);
            $model->fill($input);
            if($model->update()){
                $msg = 'Данные устройства '. $input['name'] .' изменены!';
                return redirect('devices')->with('status',$msg);
            }
        }
        $old = $model->toArray(); //сохраняем в массиве предыдущие значения полей модели Currency
        if(view()->exists('config.deviceEdit')){
            //выбираем все протоколы
            $prots = Protocol::all();
            $protsel = array();
            foreach ($prots as $val){
                $protsel[$val->id] = $val->name;
            }
            //выбираем все локации
            $locs = Location::all();
            $locsel = array();
            foreach ($locs as $val){
                $locsel[$val->id] = $val->name;
            }
            //выбираем все типы устройств
            $types = DeviceType::all();
            $typesel = array();
            foreach ($types as $val){
                $typesel[$val->id] = $val->name;
            }
            $data = [
                'title' => 'Редактирование '.$old['name'],
                'protsel' =>$protsel,
                'locsel' =>$locsel,
                'typesel' =>$typesel,
                'data' => $old,
            ];
            return view('config.deviceEdit', $data);
        }
        abort(404);
    }
}

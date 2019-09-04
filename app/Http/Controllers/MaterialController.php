<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Material;
use Illuminate\Http\Request;
use Validator;

class MaterialController extends Controller
{
    public function index(){
        if(view()->exists('refs.materials')){
            //выбираем данные из таблицы
            $rows = Material::all();
            //выбираем все категории
            $cats = Category::all();
            $catsel = array();
            foreach ($cats as $val){
                $catsel[$val->id] = $val->name;
            }
            return view('refs.materials',[
                'title'=>'Номенклатура',
                'head' => 'Номенклатура',
                'rows' => $rows,
                'catsel' =>$catsel,
            ]);
        }
        abort(404);
    }

    public function create(Request $request){

        if($request->isMethod('post')){
            $input = $request->except('_token'); //параметр _token нам не нужен
            $messages = [
                'required' => 'Поле :attribute обязательно к заполнению',
                'unique' => 'Поле :attribute должно быть уникальным',
                'integer' => 'Поле :attribute должно иметь цифровое значение',
                'mimes' => 'К загрузке допускаются только файлы jpeg,png и bmp',
            ];
            $validator = Validator::make($input,[
                'name' => 'required|unique:materials|max:100',
                'category_id' => 'required|integer',
                'image' => 'mimes:jpeg,bmp,png',
            ],$messages);
            if($validator->fails()){
                return redirect()->route('materialAdd')->withErrors($validator)->withInput();
            }
            if($request->hasFile('image')){
                $file = $request->file('image'); //загружаем файл
                //создаем произвольное имя файла во избежание проблем с кодировками!
                $fname = substr_replace(sha1(microtime(true)), '', 12);
                $input['image'] = $fname.'.'.$file->getClientOriginalExtension();
                $file->move(public_path().'/images/gallery', $input['image']);
            }
            $model = new Material();
            $model->fill($input);
            if($model->save()){
                $msg = 'Номенклатура '. $input['name'] .' добавлена!';
                return redirect('materials')->with('status',$msg);
            }
        }
        if(view()->exists('refs.materialAdd')){
            //выбираем все категории
            $cats = Category::all();
            $catsel = array();
            foreach ($cats as $val){
                $catsel[$val->id] = $val->name;
            }
            $data = [
                'title' => 'Новая номенклатура',
                'catsel' =>$catsel,
            ];
            return view('refs.materialAdd', $data);
        }

        abort(404);
    }

    public function edit(Request $request,$id=null){
        $model = Material::find($id);
        if($request->isMethod('post')){
            $input = $request->except('_token'); //параметр _token нам не нужен
            $messages = [
                'required' => 'Поле :attribute обязательно к заполнению',
                'integer' => 'Поле :attribute должно иметь цифровое значение',
                'mimes' => 'К загрузке допускаются только файлы jpeg,png и bmp',
            ];
            $validator = Validator::make($input,[
                'name' => 'required|max:100',
                'category_id' => 'required|integer',
                'image' => 'mimes:jpeg,bmp,png',
            ],$messages);
            if($validator->fails()){
                return redirect()->route('materialAdd')->withErrors($validator)->withInput();
            }
            if($request->hasFile('image')){
                $file = $request->file('image'); //загружаем файл
                //создаем произвольное имя файла во избежание проблем с кодировками!
                $fname = substr_replace(sha1(microtime(true)), '', 12);
                $input['image'] = $fname.'.'.$file->getClientOriginalExtension();
                $file->move(public_path().'/images/gallery', $input['image']);
            }
            else{
                $input['image'] = $input['old_image'];
            }
            if($input['image'] != $input['old_image']){ //загрузили новый файл, старый нужно удалить
                unlink(public_path().'/images/gallery/'.$input['old_image']);
            }
            unset($input['old_image']);
            $model->fill($input);
            if($model->update()){
                $msg = 'Данные номенклатуры '. $input['name'] .' изменены!';
                return redirect('materials')->with('status',$msg);
            }
        }
        $old = $model->toArray(); //сохраняем в массиве предыдущие значения полей модели Currency
        if(view()->exists('refs.materialEdit')){
            //выбираем все категории
            $cats = Category::all();
            $catsel = array();
            foreach ($cats as $val){
                $catsel[$val->id] = $val->name;
            }
            $data = [
                'title' => 'Редактирование '.$old['name'],
                'catsel' =>$catsel,
                'data' => $old,
            ];
            return view('refs.materialEdit', $data);
        }

        abort(404);
    }
}

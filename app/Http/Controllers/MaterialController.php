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
            ];
            $validator = Validator::make($input,[
                'name' => 'required|unique:materials|max:100',
                'group_id' => 'required|integer',
            ],$messages);
            if($validator->fails()){
                return redirect()->route('materialAdd')->withErrors($validator)->withInput();
            }
            if($request->hasFile('image')){
                $file = $request->file('image'); //загружаем файл
                $input['image'] = $file->getClientOriginalName();
                dd($input);
                $file->move(public_path().'/images/gallery', $input['image']);
            }
            $page = new Material();
            $page->fill($input);
            if($page->save()){
                $msg = 'Номенклатура '. $input['name'] .' добавлена!';
                return redirect('material')->with('status',$msg);
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
}

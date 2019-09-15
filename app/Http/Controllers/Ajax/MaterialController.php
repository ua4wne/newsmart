<?php

namespace App\Http\Controllers\Ajax;

use App\Models\Material;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use phpDocumentor\Reflection\Types\Null_;

class MaterialController extends Controller
{
    public function delete(Request $request){
        if($request->isMethod('post')){
            $id = $request->input('id');
            $model = Material::find($id);
            $file = $model->image;
            if($model->delete()) {
                if($file=='/images/noimage.jpg'){
                    return 'OK';
                }
                //удаляем файл изображения
                if(!empty($file)){
                    unlink(public_path().$file);
                }
                return 'OK';
            }
            else{
                return 'ERR';
            }
        }
    }

    public function ajaxData(Request $request){
        $query = $request->get('query','');
        $firms = Material::where('name','LIKE','%'.$query.'%')->get();
        return response()->json($firms);
    }

    public function ajaxImg(Request $request){
        $name = $request->input('material');
        if(empty($name)) return 'ERR';
        $img = Material::where(['name'=>$name])->first()->image;
        return $img;
    }

    public function ajaxCategory(Request $request){
        $name = $request->input('material');
        if(empty($name)) return 'ERR';
        $category = Material::where(['name'=>$name])->first()->category->name;
        return $category;
    }
}

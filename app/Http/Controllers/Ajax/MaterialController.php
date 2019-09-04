<?php

namespace App\Http\Controllers\Ajax;

use App\Models\Material;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MaterialController extends Controller
{
    public function delete(Request $request){
        if($request->isMethod('post')){
            $id = $request->input('id');
            $model = Material::find($id);
            $file = $model->image;
            if($model->delete()) {
                //удаляем файл изображения
                if(!empty($file)){
                    unlink(public_path().'/images/gallery/'.$file);
                }
                return 'OK';
            }
            else{
                return 'ERR';
            }
        }
    }
}

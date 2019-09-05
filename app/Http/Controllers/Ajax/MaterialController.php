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
}

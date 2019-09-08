<?php

namespace App\Http\Controllers\Ajax;

use App\Models\Device;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DeviceController extends Controller
{
    public function delete(Request $request){
        if($request->isMethod('post')){
            $id = $request->input('id');
            $model = Device::find($id);
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

<?php

namespace App\Http\Controllers\Ajax;

use App\Models\Material;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MaterialController extends Controller
{
    public function create(Request $request){
        if($request->isMethod('post')){
            $input = $request->except('_token'); //параметр _token нам не нужен
            dd($input);
            $model = new Material();
            $input['created_at'] = date('Y-m-d H:i:s');
            $file = $request->file('image'); //загружаем файл
            //$input['image'] = $file->getClientOriginalName();
            return $file->getClientOriginalName();
            if($request->hasFile('image')){
                $file = $request->file('image'); //загружаем файл
                $input['image'] = $file->getClientOriginalName();
                return $input['image'];
                $file->move(public_path().'/images/gallery', $input['image']);

                //создаем произвольное имя файла во избежание проблем с кодировками!
                $fname = substr_replace(sha1(microtime(true)), '', 12);
                $this->image->saveAs("images/gallery/{$fname}.{$this->image->extension}");
                return '/images/gallery/'.$fname.'.'.$this->image->extension;
            }
            $model->fill($input);
            if($model->save()){
                $content='<td style="width:100px;"><div class="form-group" role="group" id="'.$model->id.'">';
                $content.='<button class="btn btn-success btn-sm val_edit" type="button" data-toggle="modal" data-target="#editVal" title="Редактировать запись"><i class="fa fa-edit" aria-hidden="true"></i></button>';
                $content.='<button class="btn btn-danger btn-sm val_delete" type="button" title="Удалить запись"><i class="fa fa-trash" aria-hidden="true"></i></button></div></td>';
                return $content;
            }
            return 'ERR';
        }
    }
}

<?php

namespace App\Http\Controllers\Ajax;

use App\Models\Cell;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CellController extends Controller
{
    public function create(Request $request){
        if($request->isMethod('post')){
            $input = $request->except('_token'); //параметр _token нам не нужен
            $model = new Cell();
            $input['created_at'] = date('Y-m-d H:i:s');
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

    public function edit(Request $request){
        if($request->isMethod('post')){
            $input = $request->except('_token'); //параметр _token нам не нужен
            $model = Cell::find($input['id_val']);
            $model->name = $input['name'];
            if($model->save()){
                return 'OK';
            }
            return 'ERR';
        }
    }

    public function delete(Request $request){
        if($request->isMethod('post')){
            $id = $request->input('id');
            $model = Cell::find($id);
            if($model->delete()) {
                return 'OK';
            }
            else{
                return 'ERR';
            }
        }
    }
}

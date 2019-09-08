<?php

namespace App\Http\Controllers\Ajax;

use App\Models\Location;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;

class LocationController extends Controller
{
    public function create(Request $request)
    {
        if ($request->isMethod('post')) {
            $messages = [
                'required' => 'Поле :attribute обязательно к заполнению',
                'unique' => 'Поле :attribute должно быть уникальным',
            ];
            $validator = Validator::make($request->all(), [
                'name' => 'required|unique:locations|max:50',
                'alias' => 'required|unique:locations|max:50'
            ], $messages);
            if ($validator->passes()) {
                $input = $request->except('_token'); //параметр _token нам не нужен
                $model = new Location();
                $input['created_at'] = date('Y-m-d H:i:s');
                $model->fill($input);
                if ($model->save()) {
                    $content = '<td style="width:100px;"><div class="form-group" role="group" id="' . $model->id . '">';
                    $content .= '<button class="btn btn-success btn-sm val_edit" type="button" data-toggle="modal" data-target="#editVal" title="Редактировать запись"><i class="fa fa-edit" aria-hidden="true"></i></button>';
                    $content .= '<button class="btn btn-danger btn-sm val_delete" type="button" title="Удалить запись"><i class="fa fa-trash" aria-hidden="true"></i></button></div></td>';
                    return $content;
                }
                return 'ERR';
            }
            return response()->json(['error' => $validator->errors()->all()]);
        }
    }

    public function edit(Request $request)
    {
        if ($request->isMethod('post')) {
            $messages = [
                'unique' => 'Поле :attribute должно быть уникальным',
            ];
            $validator = Validator::make($request->all(), [
                'name' => 'required|max:50',
                'alias' => 'required|max:50'
            ], $messages);
            if ($validator->passes()) {
                $input = $request->except('_token'); //параметр _token нам не нужен
                $model = Location::find($input['id_val']);
                $model->name = $input['name'];
                $model->alias = $input['alias'];
                $model->is_show = $input['is_show'];
                if ($model->save()) {
                    return 'OK';
                }
                return 'ERR';
            }
            return response()->json(['error' => $validator->errors()->all()]);
        }
    }

    public function delete(Request $request)
    {
        if ($request->isMethod('post')) {
            $id = $request->input('id');
            $model = Location::find($id);
            if ($model->delete()) {
                /*    $msg = 'Банковская выписка '. $model->doc_num .' была удалена!';
                    //вызываем event
                    event(new AddEventLogs('info',Auth::id(),$msg));*/
                return 'OK';
            } else {
                return 'ERR';
            }
        }
    }
}

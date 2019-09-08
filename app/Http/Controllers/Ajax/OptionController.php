<?php

namespace App\Http\Controllers\Ajax;

use App\Models\Option;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;

class OptionController extends Controller
{
    public function create(Request $request)
    {
        if ($request->isMethod('post')) {
            $messages = [
                'required' => 'Поле :attribute обязательно к заполнению',
                'numeric' => 'Поле :attribute должно иметь числовое или дробное значение',
                'string' => 'Поле :attribute должно иметь строковое значение',
                'in' => 'Поле :attribute должно иметь значение 0 или 1',
            ];
            $validator = Validator::make($request->all(), [
                'device_id' => 'required|numeric',
                'address' => 'nullable|string|max:32',
                'min_val' => 'required|numeric',
                'max_val' => 'required|numeric',
                'unit' => 'nullable|string|max:7',
                'alias' => 'required|string|max:50',
                'name' => 'required|string|max:50',
                'to_log' => 'required|in:0,1',
            ], $messages);
            if ($validator->passes()) {
                $input = $request->except('_token'); //параметр _token нам не нужен
                $model = new Option();
                $input['created_at'] = date('Y-m-d H:i:s');
                $model->fill($input);
                if ($model->save()) {
                    $content = '<td style="width:140px;"><div class="form-group" role="group" id="' . $model->id . '">';
                    $content .= '<button class="btn btn-success btn-sm val_edit" type="button" data-toggle="modal" data-target="#editVal" title="Редактировать запись"><i class="fa fa-edit" aria-hidden="true"></i></button>';
                    $content .= '<a href="/rules/'. $model->id .'"><button class="btn btn-info btn-sm" type="button" title="Правила"><i class="fa fa-bell-o" aria-hidden="true"></i></button></a>';
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
                'required' => 'Поле :attribute обязательно к заполнению',
                'numeric' => 'Поле :attribute должно иметь числовое или дробное значение',
                'string' => 'Поле :attribute должно иметь строковое значение',
                'in' => 'Поле :attribute должно иметь значение 0 или 1',
            ];
            $validator = Validator::make($request->all(), [
                'address' => 'nullable|string|max:32',
                'min_val' => 'required|numeric',
                'max_val' => 'required|numeric',
                'unit' => 'nullable|string|max:7',
                'alias' => 'required|string|max:50',
                'name' => 'required|string|max:50',
                'to_log' => 'required|in:0,1',
            ], $messages);
            if ($validator->passes()) {
                $input = $request->except('_token'); //параметр _token нам не нужен
                $model = Option::find($input['id_val']);
                $model->address = $input['address'];
                $model->min_val = $input['min_val'];
                $model->max_val = $input['max_val'];
                $model->unit = $input['unit'];
                $model->alias = $input['alias'];
                $model->name = $input['name'];
                $model->to_log = $input['to_log'];
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
            $model = Option::find($id);
            if ($model->delete()) {
                return 'OK';
            } else {
                return 'ERR';
            }
        }
    }
}

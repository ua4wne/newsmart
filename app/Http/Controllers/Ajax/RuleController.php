<?php

namespace App\Http\Controllers\Ajax;

use App\Models\Rule;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;

class RuleController extends Controller
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
                'option_id' => 'required|numeric',
                'condition' => 'required|string',
                'val' => 'required|numeric',
                'action' => 'required|string',
                'text' => 'required|string',
                'step' => 'required|numeric',
                'state' => 'required|in:0,1',
            ], $messages);
            if ($validator->passes()) {
                $input = $request->except('_token'); //параметр _token нам не нужен
                $model = new Rule();
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
                'required' => 'Поле :attribute обязательно к заполнению',
                'numeric' => 'Поле :attribute должно иметь числовое или дробное значение',
                'string' => 'Поле :attribute должно иметь строковое значение',
                'in' => 'Поле :attribute должно иметь значение 0 или 1',
            ];
            $validator = Validator::make($request->all(), [
                'condition' => 'required|string',
                'val' => 'required|numeric',
                'action' => 'required|string',
                'text' => 'required|string',
                'step' => 'required|numeric',
                'state' => 'required|in:0,1',
            ], $messages);
            if ($validator->passes()) {
                $input = $request->except('_token'); //параметр _token нам не нужен
                $model = Rule::find($input['id_val']);
                $model->condition = $input['condition'];
                $model->val = $input['val'];
                $model->action = $input['action'];
                $model->text = $input['text'];
                $model->state = $input['state'];
                $model->step = $input['step'];
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
            $model = Rule::find($id);
            if ($model->delete()) {
                return 'OK';
            } else {
                return 'ERR';
            }
        }
    }
}

<?php

namespace App\Http\Controllers\Ajax;

use App\Models\Tarif;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;

class TarifController extends Controller
{
    public function create(Request $request)
    {
        if ($request->isMethod('post')) {
            $messages = [
                'required' => 'Поле :attribute обязательно к заполнению',
                'numeric' => 'Поле :attribute должно иметь числовое или дробное значение',
                'string' => 'Поле :attribute должно иметь строковое значение',
            ];
            $validator = Validator::make($request->all(), [
                'device_id' => 'required|numeric',
                'koeff' => 'required|numeric',
                'unit' => 'required|string|max:5',
            ], $messages);
            if ($validator->passes()) {
                $input = $request->except('_token'); //параметр _token нам не нужен
                $model = new Tarif();
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
            ];
            $validator = Validator::make($request->all(), [
                'koeff' => 'required|numeric',
                'unit' => 'required|string|max:5',
            ], $messages);
            if ($validator->passes()) {
                $input = $request->except('_token'); //параметр _token нам не нужен
                $model = Tarif::find($input['id_val']);
                $model->koeff = $input['koeff'];
                $model->unit = $input['unit'];
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
            $model = Tarif::find($id);
            if ($model->delete()) {
                return 'OK';
            } else {
                return 'ERR';
            }
        }
    }
}

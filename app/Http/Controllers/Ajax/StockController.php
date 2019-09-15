<?php

namespace App\Http\Controllers\Ajax;

use App\Models\Material;
use App\Models\Stock;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;

class StockController extends Controller
{
    public function create(Request $request){
        if($request->isMethod('post')){
            $messages = [
                'required' => 'Поле :attribute обязательно к заполнению',
                'numeric' => 'Поле :attribute должно иметь числовое или дробное значение',
            ];
            $validator = Validator::make($request->all(), [
                'cell_id' => 'required|numeric',
                'quantity' => 'required|numeric',
                'unit_id' => 'required|numeric',
                'price' => 'required|numeric',
            ],$messages);
            if ($validator->passes()) {
                $input = $request->except('_token','category'); //параметр _token нам не нужен
                if(isset($input['material_id'])){
                    $material = Material::where('name', $input['material_id'])->first();
                    $input['material_id'] = $material->id;
                }
                $model = new Stock();
                $input['created_at'] = date('Y-m-d H:i:s');
                $model->fill($input);
                if ($model->save()) {
                    $content = '<div class="form-group" role="group" id="' . $model->id . '">';
                    $content .= '<button class="btn btn-success btn-sm val_edit" type="button" data-toggle="modal" data-target="#editVal" title="Редактировать запись"><i class="fa fa-edit" aria-hidden="true"></i></button>';
                    $content .= '<button class="btn btn-danger btn-sm val_delete" type="button" title="Удалить запись"><i class="fa fa-trash" aria-hidden="true"></i></button></div>';
                    return $content;
                }
                return 'ERR';
            }
            return response()->json(['error'=>$validator->errors()->all()]);
        }
    }

    public function edit(Request $request){
        if($request->isMethod('post')){
            $messages = [
                'required' => 'Поле :attribute обязательно к заполнению',
                'numeric' => 'Поле :attribute должно иметь числовое или дробное значение',
            ];
            $validator = Validator::make($request->all(), [
                'cell_id' => 'required|numeric',
                'quantity' => 'required|numeric',
                'unit_id' => 'required|numeric',
                'price' => 'required|numeric',
            ],$messages);
            if ($validator->passes()) {
                $input = $request->except('_token','ecategory'); //параметр _token нам не нужен
                $model = Stock::find($input['id_val']);
                $model->cell_id = $input['cell_id'];
                $model->quantity = $input['quantity'];
                $model->unit_id = $input['unit_id'];
                $model->price = $input['price'];
                if ($model->save()) {
                    return 'OK';
                }
                return 'ERR';
            }
            return response()->json(['error'=>$validator->errors()->all()]);
        }
    }

    public function delete(Request $request){
        if($request->isMethod('post')){
            $id = $request->input('id');
            $model = Stock::find($id);
            if($model->delete()) {
                return 'OK';
            }
            else{
                return 'ERR';
            }
        }
    }
}

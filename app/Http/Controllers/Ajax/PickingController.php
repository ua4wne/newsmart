<?php

namespace App\Http\Controllers\Ajax;

use App\Models\Stock;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PickingController extends Controller
{
    public function read(Request $request)
    {
        if ($request->isMethod('post')) {
            $html = '<table id="my_datatable" class="table table-striped table-bordered">
                <thead>
                <tr>
                    <th>Фото</th>
                    <th>Ячейка</th>
                    <th>Номенклатура</th>
                    <th>Категория</th>
                    <th>Кол-во</th>
                    <th>Ед изм</th>
                    <th>Цена, руб</th>
                </tr>
                </thead>
                <tbody>';
            $input = $request->except('_token'); //параметр _token нам не нужен
            $category = $input['category'];
            $count = $input['count'];
            if(!empty($category) && !empty($count)){
                //выбираем данные из таблицы
                $rows = Stock::where('quantity','<',$count)->get();
                foreach($rows as $row){
                    if(in_array($row->material->category->id ,$category)){
                        $html .= '<tr><td style="width:80px;"><img src="' . $row->material->image . '" class="img-responsive, img_mini"></td>';
                        $html .= '<td>'.$row->cell->name.'</td><td>'.$row->material->name.'</td><td>'.$row->material->category->name.'</td><td>'.$row->quantity.'</td><td>'.$row->unit->name.'</td><td>'.$row->price.'</td></tr>';
                    }
                }
            }
            $html .= '</tbody>
            </table>';
            return $html;
        }
    }
}

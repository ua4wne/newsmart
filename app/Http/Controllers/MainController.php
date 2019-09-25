<?php

namespace App\Http\Controllers;

use App\Models\Device;
use App\Models\Location;
use App\Models\MqttData;
use App\Models\Option;
use App\Models\Topic;
use Illuminate\Http\Request;

class MainController extends Controller
{
    public function index(){
        if(view()->exists('main_index')){
            return view('main_index',[
                'title'=>'Панель',
                'device' => $this->DeviceState(),
                'tabs' => $this->GetTabs(),
            ]);
        }
        abort(404);
    }

    private function DeviceState(){
        $models = Device::where(['verify'=>'1'])->get();
        $html = '';
        foreach ($models as $model){
            $vcc = Option::where(['device_id'=>$model->id,'alias'=>'vcc'])->first();
            $val = $vcc->val;
            $min = $vcc->min_val;
            $max = $vcc->max_val;
            $mid = ($max + $min)/2;
            if($val==$max){
                $ico_vcc = '<i class="fa fa-battery-full green"></i>';
            }
            elseif($val>$min && $val<($mid)){
                $ico_vcc = '<i class="fa fa-battery-quarter"></i>';
            }
            elseif($val>$mid && $val<$max){
                $ico_vcc = '<i class="fa fa-battery-three-quarters"></i>';
            }
            elseif ($val<$min){
                $ico_vcc = '<i class="fa fa-battery-empty red"></i>';
            }
            $rssi = Option::where(['device_id'=>$model->id,'alias'=>'rssi'])->first();
            if($rssi->val > -70){
                $ico_rssi = '<i class="fa fa-signal green"></i>';
            }
            elseif($rssi->val > -80 && $rssi->val <= -70) {
                $ico_rssi = '<i class="fa fa-signal"></i>';
            }
            else{
                $ico_rssi = '<i class="fa fa-signal red"></i>';
            }
            $time_stamp = strtotime(date('Y-m-d H:i:s')); //получаем текущую метку времени
            $time_last = strtotime($vcc->updated_at);
            $period = strtotime('+30 minutes',$time_last);
            if($time_stamp < $period){
                $stat = '<span class="label label-success arrowed-right arrowed-in">online</span>';
            }
            else{
                $stat = '<span class="label label-danger arrowed-right arrowed-in">offline</span>';
            }
            $html .= '<tr>
                                            <td><a href="/options/'.$model->id.'" target="_blank">' . $model->name . '</a></td>

                                            <td>' . $ico_rssi . '&nbsp;&nbsp;&nbsp;<span class="badge">' . $rssi->val . $rssi->unit . '</span></td>

                                            <td>' . $ico_vcc . '</td>

                                            <td class="hidden-480">' . $stat . '</td>
                                        </tr>';
        }
        return $html;
    }

    private function GetTabs(){
        $html='<div class="col-xs-3">';
        $html .= '<ul class="nav nav-tabs tabs-right" id="LocationTab">';
        $devices = Device::where(['verify'=>'1'])->select('location_id')->distinct()->get();
        $locid = array();
        foreach ($devices as $device) {
            $locid[] = $device->location_id;
        }
        $locations = Location::select(['id','name','alias'])->where(['is_show'=>'1'])->whereIn('id', $locid)->orderBy('name','asc')->get();
        $k=0;
        foreach ($locations as $location){
            if($k==0)
                $html .= '<li class="active">';
            else
                $html .= '<li>';
            $html .= '<a data-toggle="tab" href="#'.$location->alias.'">' .
                $location->name
                . '</a>
                            </li>';
            $k++;
        }
        $html .= '</ul></div>';
        $k=0;
        $html .= '<div class="col-xs-9">';
        $html .= '<div class="tab-content">';
        foreach ($locations as $location){
            //определяем кол-во включенных устройств в помещении, которые контролируются автоматически
            $devices = Device::select('id')->where(['location_id'=>$location->id,'verify'=>'1','status'=>'1'])->get();
            if($k==0)
                $html .= '<div id="'.$location->alias.'" class="tab-pane active">';
            else
                $html .= '<div id="'.$location->alias.'" class="tab-pane">';
            if(!empty($devices)){ //есть такие устройства
                //определяем device_id
                $device_id = array();
                $i = 0;
                foreach ($devices as $device){
                    array_push($device_id, $device->id);
                    $i++;
                }
                //определяем параметры, привязанные к этим устройствам
                $params = Option::where(['device_id' => $device_id])->whereNotIn('alias',array('vcc','rssi'))->get();
                $step = 0;
                $html .= '<div class="row">
                            <div class="col-xs-12">';
                foreach ($params as $param){
                    if($param->alias == 'state' || $param->alias == 'light'){
                        $topic_id = Topic::where(['option_id'=>$param->id])->first()->topic_id;
                        $topic=MqttData::find($topic_id)->topic;
                        $check='';
                        if($param->val)
                            $check='checked="checked"';
                        $html .= '<div class="infobox infobox-blue2">
								        <div class="infobox-data">
										    <input type="hidden" name="'.$topic.'">
											<label>
												<input type="checkbox" name="switch-'.$param->id.'" id = "'.$param->id.'" class="ace ace-switch ace-switch-4 btn-rotate" '.$check.' >
												<span class="lbl"></span>
											</label>

											<div class="infobox-content">
												<span class="infobox-text">'.$param->name.'</span>
											</div>
										</div>
								</div>';
                    }
                    elseif($param->alias == 'alarm'){
                        if($param->val){
                            $html.= '<div class="infobox infobox-red">
											<div class="infobox-icon">
												<i class="ace-icon fa fa-bell-o red"></i>
											</div>
											<div class="infobox-data">
												<span class="infobox-data-number">' . $param->val . '</span>
												<div class="infobox-content">'.$param->name.'</div>
											</div>
									</div>';
                        }
                        else{
                            $html.= '<div class="infobox infobox-green">
											<div class="infobox-icon">
												<i class="ace-icon fa fa-bell-o green"></i>
											</div>
											<div class="infobox-data">
												<span class="infobox-data-number">Норма</span>
												<div class="infobox-content">'.$param->name.'</div>
											</div>
									</div>';
                        }
                    }
                    elseif($param->alias == 'celsio' || $param->alias == 'humidity' || $param->alias == 'power'){
                        if($param->alias == 'celsio'){
                            if($param->val <= 15)
                                $color = 'data-color="#87CEEB"';
                            if($param->val > 15 && $param->val < 30)
                                $color = 'data-color="#87B87F"';
                            if($param->val >= 30)
                                $color = 'data-color="#D15B47"';
                        }
                        if($param->alias == 'humidity'){
                            if($param->val <= 50)
                                $color = 'data-color="#FFB935"';
                            if($param->val > 50 && $param->val < 70)
                                $color = 'data-color="#87B87F"';
                            if($param->val > 70)
                                $color = 'data-color="#D15B47"';
                        }
                        $html.= '<div class="infobox infobox-blue2">
											<div class="infobox-progress">
												<div class="easy-pie-chart percentage" data-percent="' . $param->val . '" ' . $color .'>
													<span class="percent">' . $param->val . '</span>
												</div>
											</div>

											<div class="infobox-data">
												<span class="infobox-text">' . $param->val . $param->unit . '</span>
												<div class="infobox-content">'.$param->name.'</div>
											</div>
										</div>';

                    }
                    elseif($param->alias == 'pressure'){
                        if($param->val <= 740){
                            $html.= '<div class="infobox infobox-blue">
											<div class="infobox-icon">
												<i class="ace-icon fa fa-arrow-down blue"></i>
											</div>
											<div class="infobox-data">
												<span class="infobox-data-number">' . $param->val . '</span>
												<div class="infobox-content">'.$param->name.'</div>
											</div>
									</div>';
                        }
                        if($param->val > 740 && $param->val < 750){
                            $html.= '<div class="infobox infobox-green">
											<div class="infobox-icon">
												<i class="ace-icon fa fa-thumbs-o-up green"></i>
											</div>
											<div class="infobox-data">
												<span class="infobox-data-number">Норма</span>
												<div class="infobox-content">'.$param->name.'</div>
											</div>
									</div>';
                        }
                        if($param->val >= 750){
                            $html.= '<div class="infobox infobox-blue">
											<div class="infobox-icon">
												<i class="ace-icon fa fa-arrow-up blue"></i>
											</div>
											<div class="infobox-data">
												<span class="infobox-data-number">' . $param->val . '</span>
												<div class="infobox-content">'.$param->name.'</div>
											</div>
									</div>';
                        }
                    }
                    else{
                        $html.= '<div class="infobox infobox-green">
											<div class="infobox-icon">
												<i class="ace-icon fa fa-comment green"></i>
											</div>
											<div class="infobox-data">
												<span class="infobox-data-number">Норма</span>
												<div class="infobox-content">'.$param->name.'</div>
											</div>
									</div>';
                    }
                }

                $html .= '    </div>
                            </div>
                          </div>';
            }
            else{
                $html .= '     <div class="row">

                                </div>
                        </div>';
            }

            $k++;
        }
        $html .= '</div></div>';

        return $html;
    }
}

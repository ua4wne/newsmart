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
                        $html .= '<div class="col-md-4 tile-stats border-blue">
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
                            $html.= '<div class="col-md-4 tile-stats border-red">
											<div class="icon">
												<i class="fa fa-bell-o red"></i>
											</div>
											<div class="infobox-data">
												<span>' . $param->val . '</span>
												<div class="infobox-content">'.$param->name.'</div>
											</div>
									</div>';
                        }
                        else{
                            $html.= '<div class="col-md-4 tile-stats border-green">
											<div class="icon">
												<i class="fa fa-bell-o green"></i>
											</div>
											<div class="infobox-data">
												<span>Норма</span>
												<div class="infobox-content">'.$param->name.'</div>
											</div>
									</div>';
                        }
                    }
                    elseif($param->alias == 'celsio' || $param->alias == 'humidity' || $param->alias == 'power'){
                        if($param->alias == 'celsio'){
                            $html .= '
                            <div class="col-md-4 tile-stats">
                                <canvas data-type="radial-gauge"
                                    data-title="Температура"
                                    data-min-value="0"
                                    data-max-value="40"
                                    data-width="160"
                                    data-height="160"
                                    data-value="' . $param->val . '"
                                    data-units="°C"
                                    data-major-ticks="0,5,10,15,20,25,30,35,40"
                                    data-minor-ticks="2"
                                    data-highlights=\'[
                                        { "from": 0, "to": 15, "color": "rgba(135, 206, 235, 1)" },
                                        { "from": 15, "to": 30, "color": "rgba(135, 184, 127, 1)" },
                                        { "from": 30, "to": 40, "color": "rgba(209, 91, 71, 1)" }
                                    ]\'
                                    data-stroke-ticks="false"
                                    data-value-box="true"
                                    data-animation-rule="bounce"
                                    data-animation-duration="500"
                                    data-font-value="Led"
                                    data-animated-value="true"
                                    data-color-needle-start="rgba(240, 128, 128, 1)"
                                    data-color-needle-end="rgba(255, 160, 122, .9)"
                                ></canvas>
                            </div>';
                        }
                        if($param->alias == 'humidity'){
                            $html .= '
                            <div class="col-md-4 tile-stats">
                                <canvas data-type="radial-gauge"
                                    data-title="Влажность"
                                    data-min-value="0"
                                    data-max-value="100"
                                    data-width="160"
                                    data-height="160"
                                    data-value="' . $param->val . '"
                                    data-units="%"
                                    data-major-ticks="0,10,20,30,40,50,60,70,80,90,100"
                                    data-minor-ticks="2"
                                    data-highlights=\'[
                                        { "from": 0, "to": 50, "color": "rgba(135, 206, 235, 1)" },
                                        { "from": 50, "to": 70, "color": "rgba(135, 184, 127, 1)" },
                                        { "from": 70, "to": 100, "color": "rgba(209, 91, 71, 1)" }
                                    ]\'
                                    data-stroke-ticks="false"
                                    data-value-box="true"
                                    data-animation-rule="bounce"
                                    data-animation-duration="500"
                                    data-font-value="Led"
                                    data-animated-value="true"
                                    data-color-needle-start="rgba(240, 128, 128, 1)"
                                    data-color-needle-end="rgba(255, 160, 122, .9)"
                                ></canvas>
                            </div>';
                        }
                    }
                    elseif($param->alias == 'pressure'){
                        $html .= '
                            <div class="col-md-4 tile-stats">
                                <canvas data-type="radial-gauge"
                                    data-title="Давление"
                                    data-min-value="700"
                                    data-max-value="800"
                                    data-width="160"
                                    data-height="160"
                                    data-value="' . $param->val . '"
                                    data-units="мм рт ст"
                                    data-major-ticks="700,710,720,730,740,750,760,770,780,790,800"
                                    data-minor-ticks="2"
                                    data-highlights=\'[
                                        { "from": 700, "to": 740, "color": "rgba(135, 206, 235, 1)" },
                                        { "from": 740, "to": 750, "color": "rgba(135, 184, 127, 1)" },
                                        { "from": 750, "to": 800, "color": "rgba(209, 91, 71, 1)" }
                                    ]\'
                                    data-stroke-ticks="false"
                                    data-value-box="true"
                                    data-animation-rule="bounce"
                                    data-animation-duration="500"
                                    data-font-value="Led"
                                    data-animated-value="true"
                                    data-color-needle-start="rgba(240, 128, 128, 1)"
                                    data-color-needle-end="rgba(255, 160, 122, .9)"
                                ></canvas>
                            </div>';
                    }
                    else{
                        $html.= '<div class="col-md-4 tile-stats border-green">
											<div class="icon">
												<i class="fa fa-comment green"></i>
											</div>
											<div class="infobox-data">
												<span>Норма</span>
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

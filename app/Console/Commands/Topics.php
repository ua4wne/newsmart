<?php

namespace App\Console\Commands;

use App\Events\AddEventLogs;
use App\Http\Controllers\Lib\LibraryController;
use App\Models\Device;
use App\Models\MqttData;
use App\Models\Option;
use App\Models\Rule;
use App\Models\Topic;
use Illuminate\Console\Command;

class Topics extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'topic:save';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Save topics data to logger table';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //выбираем все переменные, которые передаются через mqtt
        $topics = Topic::all();
        if(!empty($topics)){
            foreach ($topics as $topic){
                $option = Option::find($topic->option_id);
                if(empty($option)) continue; //если не найден объект - пропускаем итерацию
                $oldval = $option->val;
                $value = MqttData::find($topic->topic_id)->value;
                if($oldval != $value){
                    $option->val = $value;
                    $option->save();
                    //проверяем на вхождение в диапазон min - max
                    if($value < $option->min_val){
                        //запись в лог
                        $msg = 'Значение параметра <strong>'. $option->name . ' (' . $option->device->name . ')</strong>  меньше минимально возможного! <span class="red">value=' . $value . ' min_value=' . $option->min_val . '</span>';
                        event(new AddEventLogs('error',$msg));
                    }
                    if($value > $option->max_val){
                        //запись в лог
                        $msg = 'Значение параметра <strong>'. $option->name . ' (' . $option->device->name . ')</strong>  больше максимально возможного! <span class="red">value=' . $value . ' max_value=' . $option->max_val . '</span>';
                        event(new AddEventLogs('error',$msg));
                    }
                    //ищем связанные правила
                    $rcount = Rule::where(['option_id'=>$option->id])->count();
                    if($rcount) {
                        return $rcount;
                        $device = Device::find($option->device_id);
                        $location = $device->location->name;
                        LibraryController::CheckRules($option,$location); //проверяем связанные правила
                    }
                }
            }
        }
    }
}

<?php

namespace App\Console\Commands;

use App\Http\Controllers\Lib\Weather;
use App\Models\SysConst;
use Illuminate\Console\Command;

class GetWeather extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'weather:file';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get XML file from API openweathermap.org';

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
        $api_key = SysConst::where(['param'=>'API_KEY_FORECAST'])->first()->val;
        $city_id = SysConst::where(['param'=>'CITY_ID'])->first()->val;
        $geoloc = SysConst::where(['param'=>'USE_GEOLOCATION'])->first()->val;
        if(isset($api_key)&&isset($city_id)&&isset($geoloc)){
            $model = new Weather($api_key,$city_id,$geoloc);
            $model->Forecast();
        }
        else
            echo 'Error config';
    }
}

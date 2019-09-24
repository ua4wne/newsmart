<?php

namespace App\Console\Commands;

use App\Models\SysConst;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CleanLog extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'eventlog:clean';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete events older more then X days';

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
        $period = SysConst::where(['param'=>'CLEANING_PERIOD'])->first()->val;
        if(!empty($period)){
            $date =  (new \DateTime('-'.$period.' days'))->format('Y-m-d');
            //очищаем eventlog
            if($period > 0)
                DB::table('eventlogs')->where('created_at', '<=', $date)->delete();
        }
    }
}

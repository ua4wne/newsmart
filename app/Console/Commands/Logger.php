<?php

namespace App\Console\Commands;

use App\Models\Option;
use Illuminate\Console\Command;

class Logger extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'logger:save';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Saving device values to logger table';

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
        //находим переменные, значения которых необходимо писать в лог
        $options = Option::select(['id','val'])->where(['to_log'=>'1'])->get();
        foreach ($options as $option){
            $date = date('Y-m-d H:i:s');
            $new = $option->val;
            //находим последнее значение переменной в логе
            $logger = \App\Models\Logger::where(['option_id'=>$option->id])->latest()->first();
            if(!empty($logger))
                $old = $logger->val;
            if(empty($logger)){
                //еще нет записей в логе, это первая
                $newlog = new \App\Models\Logger();
                $newlog->option_id = $option->id;
                $newlog->val = $option->val;
                $newlog->created_at = $date;
                $newlog->updated_at = $date;
                $newlog->save();
            }
            else{
                if($old != $new){
                    //значение параметра отличается от последнего сохраненного
                    $newlog = new \App\Models\Logger();
                    $newlog->option_id = $option->id;
                    $newlog->val = $option->val;
                    $newlog->created_at = $date;
                    $newlog->updated_at = $date;
                    $newlog->save();
                }
            }
        }
    }
}

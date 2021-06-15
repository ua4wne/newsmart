<?php

namespace App\Console\Commands;

use App\Events\AddEventLogs;
use App\Models\Option;
use App\Models\SysConst;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class CheckFail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:fail';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Checking data that has not been updated for more than a day';

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
        $date = date("Y-m-d"); //текущая дата
        $period = date('Y-m-d', strtotime("$date -1 day"));
        //выбираем из базы переменные, которые не обновлялись более суток
        $options = Option::select(['device_id','val','unit','name','updated_at'])->where('updated_at','<',$period)->whereNotIn('alias',['state','alarm'])->get();
        if(!empty($options)){
            //если есть такие параметры, тогда формируем из них таблицу и отправляем письмо
            $content='';
            foreach ($options as $option){
                $dname = $option->device->name;
                if($option->device->status=='1') //только для включенных устройств
                    $content.="<tr><td>$dname</td><td>$option->name</td><td>$option->val</td><td>$option->unit</td><td>$option->updated_at</td></tr>";
            }
            $to = SysConst::where(['param'=>'CONTROL_E_MAIL'])->first()->val;
            if(!empty($to) && $content!=''){
                Mail::send('emails.check_fail', array('content'=>$content), function($message) use ($to)
                {
                    $message->to($to)->subject('Ошибки считывания данных на ' . date('Y-m-d H:i:s'));
                });
                //запись в лог
                if(count(Mail::failures()) > 0){
                    $msg = 'Возникла ошибка при отправке сообщения об ошибках считывания данных в системе адресату <strong>'. $to .'</strong>';
                    event(new AddEventLogs('error',$msg));
                }
                /*else{
                    $msg = 'Сообщение об ошибках считывания данных в системе было отправлено получателю '. $to;
                    event(new AddEventLogs('info',$msg));
                }*/
            }
        }
    }
}

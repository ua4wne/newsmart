<?php

namespace App\Console\Commands;

use App\Events\AddEventLogs;
use App\Models\SysConst;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class Start extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'start:mail';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sending an email at server startup';

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
        $msg = 'Система была запущена ' . date("Y-m-d H:i:s");
        $to = SysConst::where(['param'=>'CONTROL_E_MAIL'])->first()->val;
        if(!empty($to)){
            $result = Mail::send('emails.start', array('msg'=>$msg), function($message) use ($to)
            {
                $message->to($to)->subject(config('app.name'));
            });
            //запись в лог
            if($result){
                $msg = 'Сообщение получателю '. $to .' отправлено!';
                event(new AddEventLogs('info',$msg));
            }
            else{
                $msg = 'Возникла ошибка при отправке сообщения о старте системы адресату <strong>'. $to .'</strong>';
                event(new AddEventLogs('error',$msg));
            }
        }
    }
}

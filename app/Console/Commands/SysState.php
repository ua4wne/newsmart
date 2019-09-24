<?php

namespace App\Console\Commands;

use App\Events\AddEventLogs;
use App\Models\SysConst;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SysState extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'system:state';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Server OS load data (disc, CPU, RAM etc.)';

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
        $name = strtolower(php_uname('s'));
        if (strpos($name, 'windows') !== FALSE) {
            //hdd stat
            $stat['hdd_free'] = round(disk_free_space("/") / 1024 / 1024 / 1024, 2);
            $stat['hdd_total'] = round(disk_total_space("/") / 1024 / 1024/ 1024, 2);
            $stat['hdd_used'] = $stat['hdd_total'] - $stat['hdd_free'];
            $stat['hdd_percent'] = round(sprintf('%.2f',($stat['hdd_used'] / $stat['hdd_total']) * 100), 0);
            //$content ='<tr><td>RAM</td><td>'.$stat['mem_used'].'</td><td>'.$stat['mem_total'].'</td><td>'.$stat['mem_percent'].'</td></tr>';
            $content ='<tr><td>HDD</td><td>'.$stat['hdd_used'].'</td><td>'.$stat['hdd_free'].'</td><td>'.$stat['hdd_percent'].'</td></tr>';
            $load = '';
        } elseif (strpos($name, 'linux') !== FALSE) {
            $mem_result = shell_exec("cat /proc/meminfo | grep MemTotal");
            $stat['mem_total'] = round(preg_replace("#[^0-9]+(?:\.[0-9]*)?#", "", $mem_result) / 1024 / 1024, 3);
            $mem_result = shell_exec("cat /proc/meminfo | grep MemFree");
            $stat['mem_free'] = round(preg_replace("#[^0-9]+(?:\.[0-9]*)?#", "", $mem_result) / 1024 / 1024, 3);
            $stat['mem_used'] = $stat['mem_total'] - $stat['mem_free'];
            $stat['mem_percent'] = round(sprintf('%.2f',($stat['mem_used']/$stat['mem_total']) * 100), 0);
            $content ='<tr><td>RAM</td><td>'.$stat['mem_used'].'</td><td>'.$stat['mem_total'].'</td><td>'.$stat['mem_percent'].'</td></tr>';
            //hdd stat
            $stat['hdd_free'] = round(disk_free_space("/") / 1024 / 1024 / 1024, 2);
            $stat['hdd_total'] = round(disk_total_space("/") / 1024 / 1024/ 1024, 2);
            $stat['hdd_used'] = $stat['hdd_total'] - $stat['hdd_free'];
            $stat['hdd_percent'] = round(sprintf('%.2f',($stat['hdd_used'] / $stat['hdd_total']) * 100), 0);
            $content .='<tr><td>HDD</td><td>'.$stat['hdd_used'].'</td><td>'.$stat['hdd_total'].'</td><td>'.$stat['hdd_percent'].'</td></tr>';
            $load = round(array_sum(sys_getloadavg()) / count(sys_getloadavg()), 2);
        }

        $uptime = shell_exec('uptime -p');
        $uptime = str_replace('up','',$uptime);
        $uptime = str_replace('days','d',$uptime);
        $uptime = str_replace('hours','h',$uptime);
        $uptime = str_replace('minutes','m',$uptime);
        $to = SysConst::where(['param'=>'CONTROL_E_MAIL'])->first()->val;
        if(!empty($to)){
            $result = Mail::send('emails.sys_state', array('uptime'=>$uptime,'upload'=>$load,'content'=>$content), function($message) use ($to)
            {
                $message->to($to)->subject('Состояние системы на  ' . date('Y-m-d H:i:s'));
            });
            //запись в лог
            if($result){
                $msg = 'Сообщение получателю '. $to .' отправлено!';
                event(new AddEventLogs('info',$msg));
            }
            else{
                $msg = 'Возникла ошибка при отправке ежедневного сообщения о состоянии системы адресату <strong>'. $to .'</strong>';
                event(new AddEventLogs('error',$msg));
            }
        }
    }
}

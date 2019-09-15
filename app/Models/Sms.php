<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use stdClass;

class Sms extends Model
{
    private $api_id;
    public $phone;
    public $message;
    public $from_mail = false;

    //отправка одного смс
    public function SendSms(){
        //определяем api_id
        $this->api_id = SysConst::where(['param'=>'API_KEY_SMSRU'])->first()->val;
        if(empty($this->api_id)){
            $msg = "Не найдено значение константы API_KEY_SMSRU в настройках системы! Отправка смс не возможна.";
        //    LibraryModel::AddEventLog('error',$msg);
            return 'ERR';
        }
        $smsru = new SMSRU($this->api_id); // Ваш уникальный программный ключ

        $data = new stdClass();
        $data->to = $this->phone; //'79685507780';
        $data->text = $this->message; //'Hello World'; // Текст сообщения
        // $data->from = ''; // Если у вас уже одобрен буквенный отправитель, его можно указать здесь, в противном случае будет использоваться ваш отправитель по умолчанию
        // $data->time = time() + 7*60*60; // Отложить отправку на 7 часов
        // $data->translit = 1; // Перевести все русские символы в латиницу (позволяет сэкономить на длине СМС)
        // $data->test = 1; // Позволяет выполнить запрос в тестовом режиме без реальной отправки сообщения
        // $data->partner_id = '1'; // Можно указать ваш ID партнера, если вы интегрируете код в чужую систему
        $sms = $smsru->send_one($data); // Отправка сообщения и возврат данных в переменную

        if ($sms->status == "OK") { // Запрос выполнен успешно
            //Yii::$app->session->setFlash('success', 'Сообщение успешно отправлено! ID сообщения: ' . $sms->sms_id);
            //$msg = 'Сообщение успешно отправлено! ID сообщения:' . $sms->sms_id;
            //LibraryModel::AddEventLog('info',$msg);
        } else {
            //Yii::$app->session->setFlash('error', 'В процессе отправки сообщения возникла ошибка! Код ошибки: ' . $sms->status_code .' Текст ошибки: '.$sms->status_text);
            //$msg = 'В процессе отправки сообщения возникла ошибка! Код ошибки: <strong>' . $sms->status_code .'</strong>. Текст ошибки: '.$sms->status_text;
            //LibraryModel::AddEventLog('error',$msg);
        }
        return $sms->status;
    }
}

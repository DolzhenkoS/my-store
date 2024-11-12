<?php

class Bot {
    //бот токен.
    private $token;
    //Данные которые мы получим через webhook
    public $data;
    //Массив с данными о пользователе у которого диалог с ботом
    public $user;

    //создаем экземпляр бота, при создании бота указываем токен
    public function __construct($token) {
        //сохраняем в свойства полученный токен
        $this->token = $token;
        //получаем данные от webhook
        $this->data = json_decode(file_get_contents('php://input'), true);

        //запись в лог
        // $log = date('Y-m-d H:i:s') . ' ' . print_r($this->data, true);
        // file_put_contents('../logs/log.txt', $log . "\r\n", FILE_APPEND);

        //записываем информарция о пользователе
        $this->setUser();


    }

    //Функция что бы установить пользователя в свойство user
    public function setUser() {
        //исходя из типа полученного update записываем информацию о текущем чате
        if($this->getType() == "callback_query") {
            $this->user = $this->data['callback_query']['message']['chat'];
        } elseif ($this->getType() == "inline_query") {
            $this->user = $this->data['inline_query']['from'];
        } else {
            $this->user = $this->data['message']['chat'];
        }
    }

    //получение id чата
    public function getChatId(){
        return $this->user['id'];
    }

      //получение данных пользователя
    public function getUser(){
        return $this->user;
    }
  
    //Функция что бы получить тип сообщения
    //Другие типы сообщений мы рассмотрим в следующих уроках
    public function getType(){
        if (isset($this->data['callback_query'])) {
            return "callback_query";
        } elseif (isset($this->data['inline_query'])) {
            return "inline_query";
        } elseif (isset($this->data['message']['text'])) {
            //если это простой текст боту, то вернем "message".
            return "message";
        } elseif (array_key_exists('message', $this->data)) {
            return "object_message";
        } else {
            return false;
        }
    }

    //функция что бы получить текст сообщения из полученных данных
    public function getText(){
        if ($this->getType() == "callback_query") {
            return $this->data['callback_query']['data'];
        } elseif ($this->getType() == "inline_query") {
            return $this->data['inline_query']['query'];
        }
        return $this->data['message']['text'];
    }

    /*
    отправляем запрос к API Telegram, функция получает метод отправки
    запроса и массив данных, отправляет запрос и возвращает результат в виде массива.
    Подробней в https://docs.telegid.me/start/first-bot
    */

    public function sendApiQuery($method, $data = array()) {
        $ch = curl_init('https://api.telegram.org/bot' . $this->token . '/' . $method);
        curl_setopt_array($ch, [
            CURLOPT_POST => count($data),
            CURLOPT_POSTFIELDS => http_build_query($data),
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_TIMEOUT => 10
        ]);
        $res = json_decode(curl_exec($ch), true);
        curl_close($ch);
        return $res;
    }
}

?>
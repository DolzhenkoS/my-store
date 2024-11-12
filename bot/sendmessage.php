<?php
include_once('init.php');

 function sendApiQuery($method, $data = array()) {
    $ch = curl_init('https://api.telegram.org/bot' . BOT_TOKEN . '/' . $method);
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

sendApiQuery('sendMessage', [
    'text' => $_POST['text'],
    'chat_id' => $_POST['chatid'],
    'parse_mode' => 'HTML',
]);



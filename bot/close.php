<?php
$log = date('Y-m-d H:i:s') . ' ' . "close";
file_put_contents('../logs/log.txt', $log . "\r\n", FILE_APPEND);

if (!empty($_POST['key'])) {
    file_put_contents('../logs/log.txt', $_POST['key'] . "\r\n", FILE_APPEND);
}
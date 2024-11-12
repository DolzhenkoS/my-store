<?php

   require_once '../DB/connect.php';

//выключение CORS
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Credentials: true ");
header("Access-Control-Allow-Methods: OPTIONS, GET, POST");
header("Access-Control-Allow-Headers: Content-Type, Depth, User-Agent, X-File-Size, X-Requested-With, If-Modified-Since, X-File-Name, Cache-Control");

    $id = $_GET['id'];

    //идентификатор пользователя
$result = $link->query('SELECT * FROM users WHERE ident=' . $id);
if (!$result)
   throw new MySQL_Exception($link->error);
while ($row = $result->fetch_row()) {
   $id = $row[0];
}

//находим заказ
$result = $link->query('SELECT * FROM orders WHERE client_id=' . $id);
if (!$result)
   throw new MySQL_Exception($link->error);
if (mysqli_num_rows($result) == 0) {
   //заказа нет - добавляем
   $link->query("INSERT INTO orders (client_id)  VALUES ('" . $id . "')");
   $id_order = mysqli_insert_id($link);
   $status=0;
   $last_edit = "";
} else {
   $row = $result->fetch_row();
   $id_order = $row[0];
   $status =  $row[3];
   $last_edit = $row[4];
}

//вычисляем сумму заказа
$result = $link->query('SELECT * FROM order_items WHERE order_id=' . $id_order);
if (!$result)
   throw new MySQL_Exception($link->error);
$summa = 0;
while ($row = $result->fetch_row()) {
    $summa += $row[5];
}


//вычисляем оплаты
$result = $link->query('SELECT * FROM pays WHERE order_id=' . $id_order);
if (!$result)
   throw new MySQL_Exception($link->error);
$pay = 0;
while ($row = $result->fetch_row()) {
    $pay += $row[5];
}

//номер заказа
 $number = substr($_GET['id'],strlen($_GET['id'])-3)."".$id_order;

$array = array();

$data = array("status"=>$status,"last_edit"=>$last_edit,"summa"=> $summa,"number"=>$number,"pay"=>$pay);
array_push($array,$data);


$json = json_encode($array, JSON_UNESCAPED_UNICODE);
header('Content-Type: application/json');
echo $json;



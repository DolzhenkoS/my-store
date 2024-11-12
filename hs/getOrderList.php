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
} else {
   $row = $result->fetch_row();
   $id_order = $row[0];
}

$result = $link->query("SELECT * FROM order_items WHERE order_id=".$id_order." ORDER BY good_id");
if (!$result) throw new MySQL_Exception($link->error);

$array = array();
while ($row = $result->fetch_row()) {

    $res = $link->query("SELECT * FROM goods WHERE article=".$row[2]);
    if (!$res) throw new MySQL_Exception($link->error);
    while ($r =$res->fetch_row())  {
        $name = $r[1];
        $desc = $r[2];
        $url = $r[3];
    }

    $data = array("article"=>$row[2],"quantity"=>$row[3],"price"=>$row[4],"sum"=>$row[5],"name"=>$name,"desc"=>$desc,"url"=>$url);
    array_push($array,$data);
}

$json = json_encode($array, JSON_UNESCAPED_UNICODE);
header('Content-Type: application/json');
echo $json;

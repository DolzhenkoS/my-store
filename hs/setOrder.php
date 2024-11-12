<?php
require_once '../DB/connect.php';

//выключение CORS
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Credentials: true ");
header("Access-Control-Allow-Methods: OPTIONS, GET, POST");
header("Access-Control-Allow-Headers: Content-Type, Depth, User-Agent, X-File-Size, X-Requested-With, If-Modified-Since, X-File-Name, Cache-Control");


// $id = $_POST['id'];
// $art = $_POST['art'];
// $q = $_POST['q'];

$id = $_GET['id'];
$art = $_GET['art'];
$q = $_GET['q'];


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
   $link->query("INSERT INTO orders (client_id,last_edit)  VALUES ('" . $id . "',NOW())");
   $id_order = mysqli_insert_id($link);
} else {
   $row = $result->fetch_row();
   $id_order = $row[0];
   $result = $link->query('UPDATE orders SET last_edit=NOW() WHERE id=' . $id_order);
}

// $price = 230;
// $maxq = 10; //максимальное доступное количество вычислять

//находим карточку
$result = $link->query('SELECT * FROM goods WHERE article=' . $art);
if (!$result)
   throw new MySQL_Exception($link->error);
while ($row = $result->fetch_row()) {
   $price = $row[4];
   $maxq = $row[6]; //максимальное доступное количество всего
}

//находим уже заказанное количество в других заказах
$result = $link->query('SELECT * FROM order_items WHERE good_id=' . $art . ' and order_id <>' . $id_order);
if (!$result)
   throw new MySQL_Exception($link->error);
$rezerv = 0;
while ($row = $result->fetch_row()) {
   $rezerv += $row[3];
   //нужно исключить из резерва старые неподтвержденные заказы (и возможно неоплаченные)
}
$maxq = max(0, $maxq - $rezerv);


//находим строку в заказе
$result = $link->query('SELECT * FROM order_items WHERE good_id=' . $art . ' and order_id=' . $id_order);
if (!$result)
   throw new MySQL_Exception($link->error);
if (mysqli_num_rows($result) == 0) {
   //нет строки в заказе - добавляем
   if ($q <= $maxq) {
      if ($q>0) {
      $link->query("INSERT INTO order_items (order_id, good_id, quantity, price, sum)  VALUES ('" . $id_order . "','" . $art . "','" . $q . "','" . $price . "','" . $price * $q . "')");
      $id_item = mysqli_insert_id($link);}
   } else {
      echo "Нет свободных остатков !";
      return "none";
   }
} else {
   $row = $result->fetch_row();
   $id_item = $row[0];
   //   $q = $q + $row[3]; //новое количество
   $sum = $row[4] * $q; //новая сумма
   if ((int)$q == 0) {
      $link->query("DELETE FROM order_items WHERE id=" . $id_item);
   } else if ($q <= $maxq) {
      $link->query("UPDATE order_items SET quantity=" . $q . ", sum=" . $sum . " WHERE id=" . $id_item);
   } else {
      echo "Нет свободных остатков !";
      return "none";
   }
}
;




// $log = date('Y-m-d H:i:s') . ' ' . ($id_item);
// // $log = date('Y-m-d H:i:s') . ' ' . print_r($result, true);
//  file_put_contents('../logs/log.txt', $log . "\r\n", FILE_APPEND);



echo "ok";




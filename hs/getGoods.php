<?php
	require_once '../DB/connect.php';
	
    $id = $_GET['id'];

//выключение CORS
	header("Access-Control-Allow-Origin: *");
	header("Access-Control-Allow-Credentials: true ");
	header("Access-Control-Allow-Methods: OPTIONS, GET, POST");
	header("Access-Control-Allow-Headers: Content-Type, Depth, User-Agent, X-File-Size, X-Requested-With, If-Modified-Since, X-File-Name, Cache-Control");

	//заказанные товары
	$text2 = "
	select good_id,quantity from order_items left join orders on order_items.order_id=orders.id left join users on orders.client_id=users.id where users.ident=".$id;
	$result2 = $link->query($text2);
	if (!$result2) throw new MySQL_Exception($link->error);

	//все товары
	$text = "
	select any_value(name) as name,any_value(url) as url,article,
	any_value(ifnull(max_count,0)) as maxq,sum(ifnull(quantity,0)) as rezerv, any_value(goods.price) as price, any_value(goods.description) as description
	from goods left join order_items on goods.article=order_items.good_id group by article";

	$result1 = $link->query($text);
	if (!$result1) throw new MySQL_Exception($link->error);

	$arr2 = array();
	while ($row = $result2->fetch_row()) {
		$data = array("art"=>$row[0],"quantity"=>$row[1]);
		array_push($arr2,$data);
	}


	$array = array();
	while ($row = $result1->fetch_row()) {
		//заказано
		$q = 0;
		foreach ($arr2 as $value) {
			if ($value["art"]==$row[2]) {
				$q = $value["quantity"];
			}
		}

		$data = array("name"=>$row[0],"url"=>$row[1],"article"=>$row[2],"maxq"=>$row[3],"rezerv"=>$row[4],"quantity"=>$q,"price"=>$row[5],"description"=>$row[6]);
		array_push($array,$data);
	}
	
	// $result1 = $link->query("SELECT * FROM goods");
	// if (!$result1) throw new MySQL_Exception($link->error);

	// $array = array();
	// while ($row = $result1->fetch_row()) {
	// 	$data = array("id"=>$row[0],"name"=>$row[1],"desc"=>$row[2],"url"=>$row[3],"price"=>$row[4],"article"=>$row[5]);
	// 	array_push($array,$data);
	// }

	$json = json_encode($array, JSON_UNESCAPED_UNICODE);
	header('Content-Type: application/json');
	echo $json;

?>
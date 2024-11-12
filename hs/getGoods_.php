<?php
	require_once '../DB/connect.php';

	$text = "
	select any_value(name) as name,any_value(url) as url,article,
	any_value(ifnull(max_count,0)) as maxq,sum(ifnull(quantity,0)) as rezerv
	from goods left join order_items on goods.article=order_items.good_id group by article";

	$result1 = $link->query($text);
	if (!$result1) throw new MySQL_Exception($link->error);

	$array = array();
	while ($row = $result1->fetch_row()) {
		$data = array("name"=>$row[0],"url"=>$row[1],"article"=>$row[2],"maxq"=>$row[3],"rezerv"=>$row[4]);
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
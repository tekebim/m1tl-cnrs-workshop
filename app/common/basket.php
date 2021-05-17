<?php
$ajax = false;
$need_auth = false;
$want_menu = true;
require_once("./common/init.php");

if(!isset($_SESSION["basket"])){
	$_SESSION["basket"] = [];
}
$basket = $_SESSION["basket"];
$tables = ["record","production","speaker"];
$metas = [];

for($i=0;$i<count($tables);$i++){
	$metas[$tables[$i]] = [];
	$SQL = "select * from `meta` where active = 1 and table_name = '".$tables[$i]."'";
	$res = $dbh->executeQuery($SQL);

	while($row = $res->fetch_assoc()){
		$metas[$tables[$i]][] = $row;
	}
}

$values = [];
if(count($basket) > 0){
	$wheres = ["record_id IN (".join(",",$basket).")"];
}else{
	$wheres = ["0=1"];
}
$SQL = "SELECT r.id as record_id, r.*, p.*, s.* from record_flat as r inner join production_flat as p on p.record_id = r.id inner join speaker_flat as s on s.id = p.speaker_id";
$SQL .= " WHERE ".join(" AND ",$wheres);
$SQL .= " ORDER BY r.id ASC, p.sequence_order ASC;";



$res = $dbh->executeQuery($SQL);
$data = [];
while($row = $res->fetch_assoc()){
	if(!isset($data[$row["record_id"]])){
		$data[$row["record_id"]] = [];
	}
	$row["in_basket"] = in_array($row["record_id"],$basket);
	$data[$row["record_id"]][] = $row;
}

echo $twig->render('index.twig', array("metas"=>$metas, "values"=>$values, "results"=>$data, "basket"=>true));
?>
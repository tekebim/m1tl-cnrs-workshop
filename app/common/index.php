<?php
$ajax = false;
$need_auth = false;
$want_menu = true;
require_once("./common/init.php");

function str_to_sqldate($str){
	$tmp = explode("/",$str);
	return $tmp[2]."-".$tmp[1]."-".$tmp[0];
}

$tables = ["record","production","speaker"];

$metas = [];
$metas_field = [];
for($i=0;$i<count($tables);$i++){
	$metas[$tables[$i]] = [];
}

$metas["record"][] = array("id"=>"-1","field_name"=>"filename", "type"=>"txt", "label"=>"Filename");
$metas_field["-1"] = array("field_name"=>"filename", "type"=>"txt");


for($i=0;$i<count($tables);$i++){
	$SQL = 'select m.*, CASE WHEN m.type = "int" THEN min(mv.int_val) WHEN m.type = "float" THEN min(mv.float_val) ELSE NULL END as minval, CASE WHEN m.type = "int" THEN max(mv.int_val) WHEN m.type = "float" THEN max(mv.float_val) ELSE NULL END as maxval from `meta` as m left join `meta_values` as mv on mv.meta_id = m.id where m.active = 1 and m.table_name = "'.$tables[$i].'" group by m.id';
	$res = $dbh->executeQuery($SQL);

	while($row = $res->fetch_assoc()){
		$metas[$tables[$i]][] = $row;
		$metas_field[$row["id"]] = array("field_name"=>$row["field_name"], "type"=>$row["type"]);
	}
}



$values = [];
if(isset($_POST["value"])){
	$values = $_POST["value"];
}



$wheres = ["1=1"];
foreach($values as $meta_id=>$vals){
	$type = $metas_field[$meta_id]["type"];
	$fname = $metas_field[$meta_id]["field_name"];
	$min = -1;
	$max = -1;
	if (is_array($vals)){
		if($type == "date"){
			if( $vals[0]!= ''){ $min = str_to_sqldate($vals[0]);}
			if( $vals[1]!= ''){ $max = str_to_sqldate($vals[1]);}
		}
		if($type == "int"){
			if( $vals[0]!= ''){ $min = intval($vals[0]);}
			if( $vals[1]!= ''){ $max = intval($vals[1]);}
		}
		if($type == "float"){
			if( $vals[0]!= ''){ $min = floatval($vals[0]);}
			if( $vals[1]!= ''){ $max = floatval($vals[1]);}
		}
	}else{
		$min = $vals;
	}
	if ($type == "int" || $type == "float") {
		if($min != -1 && $max != -1){
			$wheres[] = $fname." BETWEEN ".$min." AND ".$max;
		}
		if($min != -1 && $max == -1){
			$wheres[] = $fname." >= ".$min;
		}
		if($min == -1 && $max != -1){
			$wheres[] = $fname." <= ".$max;
		}
	}
	if ($type == "date") {
		if($min != -1 && $max != -1){
			$wheres[] = $fname." BETWEEN '".$min."' AND '".$max."'";
		}
		if($min != -1 && $max == -1){
			$wheres[] = $fname." >= '".$min."'";
		}
		if($min == -1 && $max != -1){
			$wheres[] = $fname." <= '".$max."'";
		}
	}
	if($type == "txt"){
		if($min != ""){
			$wheres[] = $fname." LIKE '%".$min."%' ";
		}
	}
	
		
}
$SQL = "SELECT r.id as record_id, r.*, p.*, s.* from record_flat as r inner join production_flat as p on p.record_id = r.id inner join speaker_flat as s on s.id = p.speaker_id";
$SQL .= " WHERE ".join(" AND ",$wheres);
$SQL .= " ORDER BY r.id ASC, p.sequence_order ASC;";

if(!isset($_SESSION["basket"])){
	$_SESSION["basket"] = [];
}
$basket = $_SESSION["basket"];

$res = $dbh->executeQuery($SQL);
$data = [];
while($row = $res->fetch_assoc()){
	if(!isset($data[$row["record_id"]])){
		$data[$row["record_id"]] = [];
	}
	$row["in_basket"] = in_array($row["record_id"],$basket);
	$data[$row["record_id"]][] = $row;
}

//var_dump($data);

echo $twig->render('index.twig', array("metas"=>$metas, "values"=>$values, "results"=>$data, "basket"=>false));
?>
<?php
$ajax = false;
$need_auth = true;
$want_menu = true;
require_once("./common/init.php");

$table_name = (isset($_GET["table"])?$_GET["table"]:'');
$act = (isset($_POST["act"])?$_POST["act"]:Null);
if($table_name == ''){
	$table_name = (isset($_POST["table"])?$_POST["table"]:'record');
}

$meta_id = (isset($_GET["metaid"])?intval($_GET["metaid"]):0);
if($meta_id == 0){
	$meta_id = (isset($_POST["metaid"])?intval($_POST["metaid"]):-1);
}

$meta = ["id"=>-1, "table_name"=>$table_name];
$allowed_types =  $dbh->getEnumValues("meta","type");


if ($act == "save"){
	if($meta_id == -1){
		$SQL = "SELECT id from meta where field_name = '".$_POST["field_name"]."'";
		$res = $dbh->executeQuery($SQL);
		if($res->num_rows != 0){
			echo "Nom de champ déjà utilisé pour cette table ou pour une autre";
			$meta["field_name"] = $_POST["field_name"];
			$meta["type"] = $_POST["type"];
			$meta["label"] = $_POST["label"];
			$meta["active"] = $_POST["active"];
		}else{			
			$SQL = "INSERT INTO `meta`(`table_name`, `field_name`, `type`, `label`, `active`) ";
			$SQL .= "VALUES ('".$table_name."', '".$_POST["field_name"]."','".$_POST["type"]."', '".$_POST["label"]."', ".intval($_POST["active"])." )";
			$meta_id = $dbh->executeInsertQueryAutoincrement($SQL);
			
			header("location: manage_meta.php?table=".$table_name);
			exit();
			
		}
	}else{
		$SQL = "UPDATE meta SET label = '".$_POST["label"]."', field_name = '".$_POST["field_name"]."', active = ".intval($_POST["active"])." WHERE id = ".$meta_id;
		$dbh->executeQuery($SQL, false);
		header("location: manage_meta.php?table=".$table_name);
		exit();
	}
}


if($meta_id > 0){
	$SQL = "select * from `meta` where id = ".$meta_id;
	$res = $dbh->executeQuery($SQL);
	while($row = $res->fetch_assoc()){
		$meta = $row;
	}
}

echo $twig->render('edit_meta.twig', array(
	"table"=>$table_name, "meta"=>$meta, "types"=>$allowed_types
));
?>
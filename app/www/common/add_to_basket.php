<?php
$ajax = true;
$need_auth = false;
$want_menu = false;
require_once("./common/init.php");

$record_id = (isset($_POST["rid"])?intval($_POST["rid"]):Null);
$act = (isset($_POST["act"])?$_POST["act"]:Null);

if(!isset($_SESSION["basket"])){
	$_SESSION["basket"] = [];
}
$basket = $_SESSION["basket"];
if($act == "add"){
	if(!in_array($record_id, $basket)){
		$basket[] = $record_id;
	}
}
if($act == "rem"){
	if(in_array($record_id, $basket)){
		$basket = array_diff($basket, array($record_id));
	}
}
	
$_SESSION["basket"] = $basket;
echo $act;
?>
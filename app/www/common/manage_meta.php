<?php
$ajax = false;
$need_auth = true;
$want_menu = true;
require_once("./common/init.php");

$table_name = (isset($_GET["table"])?$_GET["table"]:'');
if($table_name == ''){
	$table_name = (isset($_POST["table"])?$_POST["table"]:'record');
}


$required_fields = [];
$meta_fields = [];

$SQL = "SELECT `COLUMN_NAME`, `DATA_TYPE` , `COLUMN_TYPE`, `EXTRA`
	FROM `INFORMATION_SCHEMA`.`COLUMNS` 
	WHERE `TABLE_SCHEMA`='cri_singe' 
		AND `TABLE_NAME`='".$table_name."';";
$res = $dbh->executeQuery($SQL);

while($row = $res->fetch_assoc()){
	$required_fields[] = $row["COLUMN_NAME"];
	
}

$SQL = "select * from `meta` where table_name = '".$table_name."'";
$res = $dbh->executeQuery($SQL);
while($row = $res->fetch_assoc()){
	$meta_fields[] = $row;
}

//var_dump($required_fields, $meta_fields);	

echo $twig->render('manage_meta.twig', array(
	"table"=>$table_name, "required"=>$required_fields, "meta"=>$meta_fields
));
?>
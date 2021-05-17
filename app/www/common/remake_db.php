<?php
$ajax = false;
$need_auth = true;
$want_menu = true;
require_once("./common/init.php");


function flat_table($dbh, $table_name){
	$SQLs = [];
	$main_fields = [];
	$SQLs[] =  "DROP TABLE IF EXISTS `".$table_name."_flat`;";

	$SQL = "SELECT `COLUMN_NAME`, `DATA_TYPE` , `COLUMN_TYPE`, `EXTRA`
	FROM `INFORMATION_SCHEMA`.`COLUMNS` 
	WHERE `TABLE_SCHEMA`='cri_singe' 
		AND `TABLE_NAME`='".$table_name."';";
	$res = $dbh->executeQuery($SQL);

	$final_SQL = "CREATE TABLE `".$table_name."_flat` (";
	while($row = $res->fetch_assoc()){
		$main_fields[] = $row["COLUMN_NAME"];
		$final_SQL .=  "`".$row["COLUMN_NAME"]."` ".$row["COLUMN_TYPE"]." NOT NULL,";
	}

	$SQL = "select * from `meta` where active = 1 and table_name = '".$table_name."'";
	$res = $dbh->executeQuery($SQL);
	while($row = $res->fetch_assoc()){
		$final_SQL .=  "`".$row["field_name"]."` ";
		switch($row["type"]) {
			case "int":
				$final_SQL .= "int(11) DEFAULT NULL,";
				break;
			case "txt":
				$final_SQL .= "text,";
				break;
			case "date":
				$final_SQL .= "datetime DEFAULT NULL,";
				break;
			case "float":
				$final_SQL .= "float DEFAULT NULL,";
				break;
		}
	}
	$final_SQL .= "PRIMARY KEY (`id`)";
	$final_SQL .= ")ENGINE=InnoDB DEFAULT CHARSET=utf8;";
	$SQLs[] = $final_SQL;

	$SQLs[] = "INSERT INTO `".$table_name."_flat` (`".join("`,`",$main_fields)."`) SELECT `".join("`,`",$main_fields)."` FROM `".$table_name."`;";


	$SQL = "select t.id, IF( meta.type = 'txt', tv.txt_val, IF(meta.type = 'int', tv.int_val , IF(meta.type = 'float', tv.float_val , tv.date_val))) as val, meta.field_name from
	".$table_name." as t 
	inner join meta on meta.active = 1 and meta.table_name = '".$table_name."'
	left outer join meta_values as tv on tv.tuple_id = t.id and tv.meta_id = meta.id
	order by t.id";

	$res = $dbh->executeQuery($SQL);
	while($row = $res->fetch_assoc()){
		if(!is_null($row["val"])){
			$SQLs[] = "UPDATE `".$table_name."_flat` SET `".$row['field_name']."` = '".$row["val"]."' WHERE `id` = ".$row["id"].";";
		}
	}

	return $SQLs;
}

$SQLs = flat_table($dbh,"record");
foreach ($SQLs as $s){
	var_dump($s);
	$dbh->executeQuery($s, false);
}

$SQLs = flat_table($dbh,"production");
foreach ($SQLs as $s){
	var_dump($s);
	$dbh->executeQuery($s, false);
}

$SQLs = flat_table($dbh,"speaker");
foreach ($SQLs as $s){
	var_dump($s);
	$dbh->executeQuery($s, false);
}


?>
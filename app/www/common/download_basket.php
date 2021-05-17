<?php
$ajax = true;
$need_auth = false;
$want_menu = false;
require_once("./common/init.php");

if(!isset($_SESSION["basket"])){
	$_SESSION["basket"] = [];
}
$basket = $_SESSION["basket"];
if(count($basket) > 0){
	$wheres = ["r.id IN (".join(",",$basket).")"];
}else{
	$wheres = ["0=1"];
}
$SQL = "SELECT DISTINCT r.filename from record_flat as r";
$SQL .= " WHERE ".join(" AND ",$wheres)." ";
$SQL .= "UNION SELECT DISTINCT r.filename from extra_files as r";
$tmp = " WHERE ".join(" AND ",$wheres);
$SQL .= str_replace(" r.id ", " r.record_id ", $tmp);


$res = $dbh->executeQuery($SQL);
$files = [];
while($row = $res->fetch_assoc()){
	$files[] = "./files/".$row["filename"];
}

$zipname = './tmp/'.md5(time()).'.zip';
$zip = new ZipArchive;
$zip->open($zipname, ZipArchive::CREATE);
foreach ($files as $file) {
	$tmp = explode("/",$file);
	$zip->addFile($file, $tmp[count($tmp)-1]);
}
$zip->close();

header('Content-Type: application/zip');
header('Content-disposition: attachment; filename=file.zip');
header('Content-Length: ' . filesize($zipname));
readfile($zipname);

unlink($zipname);
?>
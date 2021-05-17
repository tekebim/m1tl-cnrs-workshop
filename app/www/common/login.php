<?php
$need_auth = false;
$want_menu = false;
require_once("./common/init.php");

$act = (isset($_POST["act"])?$_POST["act"]:Null);
$logout = (isset($_GET["logout"])?intval($_GET["logout"]):-1);
if($act == "login"){

	$SQL = "Select `id` from `user` where `login` = '".$_POST["login"]."' and `password` = '".$_POST["pass"]."'";
	$res = $dbh->executeQuery($SQL);
	if($res->num_rows == 1){
			$t = $res->fetch_assoc();
			$user_id = $t["id"];
			$_SESSION["uid"] = $user_id;
			header('Location: index.php');
			exit();
	}
	
}
if($logout != -1){
	session_destroy();
	header('Location: index.php');
	exit();
}

echo $twig->render('login.twig');

?>
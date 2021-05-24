<?php
$ajax = false;
$need_auth = false;
$want_menu = true;
require_once("./common/init.php");

$pid = (isset($_GET["pid"]) ? intval($_GET["pid"]) : 1);

echo '<main id="page-about">';
echo $twig->render('pages/about.twig', array("pid" => $pid));
echo '</main>';

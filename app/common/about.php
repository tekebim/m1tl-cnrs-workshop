<?php
$ajax = false;
$need_auth = false;
$want_menu = true;
require_once("environnement.php");
require_once($_ENV["RELATIVE_PATH"] . "common/init.php");

$pid = (isset($_GET["pid"]) ? intval($_GET["pid"]) : 1);

echo '<main id="page-about">';
echo $twig->render('about.twig', array("pid" => $pid));
echo '</main>';

<?php
$ajax = false;
$need_auth = false;
$want_menu = true;
require_once("environnement.php");
require_once($_ENV["RELATIVE_PATH"] . "common/init.php");

echo $twig->render('pages/basket.twig', array(
        "metas" => $metas,
        "values" => $values,
        "results" => $data
    )
);

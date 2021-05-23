<?php
$ajax = false;
$need_auth = false;
$want_menu = true;
require_once("./common/init.php");

echo $twig->render('pages/basket.twig', array(
        "metas" => $metas,
        "values" => $values,
        "results" => $data
    )
);

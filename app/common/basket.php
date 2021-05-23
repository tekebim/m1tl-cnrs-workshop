<?php
$ajax = false;
$need_auth = false;
$want_menu = true;
require_once("environnement.php");
require_once($_ENV["RELATIVE_PATH"] . "common/init.php");

// Filtering by url query opened for all users
if (isset($_GET["ids"])) {
    // http://workshop-cnrs.docker/muriquis/index.php?ids[]=1&ids[]=2&ids[]=3
    $IDs = $_GET["ids"];
} // Dedicated user selection


echo $twig->render('pages/basket.twig', array(
        "metas" => $metas,
        "values" => $values,
        "results" => $data,
        "currentPage" => "basket",
        "projectName" => _PROJECT_NAME_
    )
);

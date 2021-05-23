<?php
#ini_set('display_errors',1); # uncomment if you need debugging
require_once("environnement.php");

spl_autoload_register(function ($classname) {
    $dirs = array(
        $_ENV['RELATIVE_PATH'] . 'common/vendors/'
    );

    foreach ($dirs as $dir) {
        $filename = $dir . str_replace('\\', '/', $classname) . '.php';
        if (file_exists($filename)) {
            require_once $filename;
            break;
        }
    }

});

// $loader = new \Twig\Loader\FilesystemLoader(['./local_templates','../common/templates']);
$loader = new \Twig\Loader\FilesystemLoader([__DIR__ . '/templates', './local_templates']);
$twig = new \Twig\Environment($loader, [
    'cache' => false,
    'debug' => true
]);

$twig->addExtension(new \Twig\Extension\DebugExtension());

// require_once '/path/to/vendor/autoload.php';
// require_once("Twig/Autoloader.php");
// Twig_Autoloader::register();
// $loader = new Twig_Loader_Filesystem(['./local_templates','./common/templates']); // Dossier contenant les templates
// $twig = new Twig_Environment($loader, array(
// 'cache' => false
// ));
if (isset($ajax)) {
    $twig->addGlobal('ajax', $ajax);
} else {
    $twig->addGlobal('ajax', false);
}
if (isset($want_menu)) {
    $twig->addGlobal('want_menu', $want_menu);
} else {
    $twig->addGlobal('want_menu', true);
}
if (isset($need_auth)) {
    $twig->addGlobal('need_auth', $need_auth);
} else {
    $twig->addGlobal('need_auth', false);
}

require_once("./incl/DBParam.php");
require_once("DatabaseHandler.php");

$dbh = new DatabaseHandler($dbHost, $dbUser, $dbPassword, $dbName, $port);

#Identification
$user_id = -1;
session_start();
if (isset($_SESSION["uid"])) {
    $user_id = $_SESSION["uid"];
}

$twig->addGlobal('user_id', $user_id);

if ($need_auth and $user_id == -1) {
    if ($ajax) {
        echo "Authentification requise";
    } else {
        header('Location: login.php');
    }
    exit();
}

// For basket
if (!isset($_SESSION["basket"])) {
    $_SESSION["basket"] = [];
}

$basket = $_SESSION["basket"];
$tables = ["record", "production", "speaker"];
$metas = [];

for ($i = 0; $i < count($tables); $i++) {
    $metas[$tables[$i]] = [];
    $SQL = "select * from `meta` where active = 1 and table_name = '" . $tables[$i] . "'";
    $res = $dbh->executeQuery($SQL);

    while ($row = $res->fetch_assoc()) {
        $metas[$tables[$i]][] = $row;
    }
}

$values = [];
if (count($basket) > 0) {
    $wheres = ["record_id IN (" . join(",", $basket) . ")"];
} else {
    $wheres = ["0=1"];
}
$SQL = "SELECT r.id as record_id, r.*, p.*, s.* from record_flat as r inner join production_flat as p on p.record_id = r.id inner join speaker_flat as s on s.id = p.speaker_id";
$SQL .= " WHERE " . join(" AND ", $wheres);
$SQL .= " ORDER BY r.id ASC, p.sequence_order ASC;";

$res = $dbh->executeQuery($SQL);
$data = [];
while ($row = $res->fetch_assoc()) {
    if (!isset($data[$row["record_id"]])) {
        $data[$row["record_id"]] = [];
    }
    $row["in_basket"] = in_array($row["record_id"], $basket);
    $data[$row["record_id"]][] = $row;
}
$twig->addGlobal('sessionBasket', $basket);
$twig->addGlobal('projectName', _PROJECT_NAME_);

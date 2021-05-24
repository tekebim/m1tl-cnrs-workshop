<?php
#ini_set('display_errors',1); # uncomment if you need debugging

spl_autoload_register(function ($classname) {
    $dirs = array(
        ROOT_DIR . "/common/vendors/"
    );

    foreach ($dirs as $dir) {
        $filename = $dir . str_replace('\\', '/', $classname) . '.php';
        if (file_exists($filename)) {
            require_once $filename;
            break;
        }
    }

});

$loader = new \Twig\Loader\FilesystemLoader([ROOT_DIR . "/local_templates", ROOT_DIR . "/common/templates"]);
$twig = new \Twig\Environment($loader, [
    'cache' => false,
    'debug' => true
]);

$twig->addExtension(new \Twig\Extension\DebugExtension());

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

require_once(ROOT_DIR . "/incl/DBParam.php");
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
$twig->addGlobal('projectSlug', _PROJECT_SLUG_);
$twig->addGlobal('projectURL', getCurrentURL('root'));
$twig->addGlobal('currentWebsiteURL', getCurrentURL('website'));
$twig->addGlobal('currentPageURL', getCurrentURL('page'));


$twig->addGlobal('menu', getItemsMenu());

getItemsMenu();
/**
 * Function to generate current URL
 * @param $type
 * @return array|string|string[]
 */
function getCurrentURL($type)
{
    if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on')
        $url = "https://";
    else
        $url = "http://";

    if ($type === 'page') {
        $url .= $_SERVER['HTTP_HOST'];
        $url .= $_SERVER['REQUEST_URI'];
    } else if ($type === 'root') {
        $url .= $_SERVER['SERVER_NAME'];
    } else if ($type === 'website') {
        $url .= $_SERVER['HTTP_HOST'];
        $url .= $_SERVER['REQUEST_URI'];
        $basename = basename($url);
        $urlDirPath = str_replace($basename, '', $url);
        $url = $urlDirPath;
    }

    return $url;
}

/*
 * Function to get items menu element
 */
function getItemsMenu()
{
    // Load project config
    $fileConfigPath = ROOT_DIR . "/config/project.json";
    $fileConfig = file_get_contents($fileConfigPath);

    $itemsMenu = [];
    // If config file exist
    if ($fileConfig !== false) {
        $configJson = json_decode(stripslashes($fileConfig), true);
        // If configuration file is json
        if ($configJson !== null) {
            // If trying to rename the slug
            if ($configJson['pages']) {
                $pages = $configJson['pages'];
                foreach ($pages as $page => $pageConfig) {
                    if ($pageConfig['isActive']) {
                        if ($pageConfig['enableSubpages']) {
                            if (count($pageConfig['subpages']) > 0) {
                                $subitems = [];
                                foreach ($pageConfig['subpages'] as $subpage => $subpageConfig) {
                                    $subitems[] = ['id' => $subpageConfig['id'], 'title' => $subpageConfig['title']];
                                }
                                $itemsMenu[] = ['title' => $pageConfig['title'], 'url' => $pageConfig['url'], 'subitems' => $subitems];
                            }
                        } else {
                            $itemsMenu[] = ['title' => $pageConfig['title'], 'url' => $pageConfig['url']];
                        }
                    }
                }
            }
        }
        return $itemsMenu;
    }
}

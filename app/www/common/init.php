<?php
#ini_set('display_errors',1); # uncomment if you need debugging

spl_autoload_register(function ($classname) {
    $dirs = array (
        './common/Twig-3.x/' #./path/to/dir_where_src_renamed_to_Twig_is_in
    );

    foreach ($dirs as $dir) {
        $filename = $dir . str_replace('\\', '/', $classname) .'.php';
        if (file_exists($filename)) {
            require_once $filename;
            break;
        }
    }

});

$loader = new \Twig\Loader\FilesystemLoader(['./local_templates','./common/templates']);
$twig = new \Twig\Environment($loader, [
    'cache' => false,
]);

// require_once '/path/to/vendor/autoload.php';
// require_once("Twig/Autoloader.php");
// Twig_Autoloader::register();
// $loader = new Twig_Loader_Filesystem(['./local_templates','./common/templates']); // Dossier contenant les templates
// $twig = new Twig_Environment($loader, array(
  // 'cache' => false
// ));
if(isset($ajax)){
	$twig->addGlobal('ajax', $ajax);
}else{
	$twig->addGlobal('ajax', false);
}
if(isset($want_menu)){
	$twig->addGlobal('want_menu', $want_menu);
}else{
	$twig->addGlobal('want_menu', false);
}
if(isset($need_auth)){
	$twig->addGlobal('need_auth', $need_auth);
}else{
	$twig->addGlobal('need_auth', false);
}
require_once("./incl/DBParam.php");
require_once("./common/DatabaseHandler.php");

$dbh = new DatabaseHandler($dbHost, $dbUser, $dbPassword, $dbName, $port);

#Identification
$user_id = -1;
session_start();
if(isset($_SESSION["uid"])){
	$user_id = $_SESSION["uid"];
}

$twig->addGlobal('user_id', $user_id);

if($need_auth and $user_id == -1){
	if($ajax){
		echo "Authentification requise";
	}else{
		header('Location: login.php');
	}
	exit();
}
?>
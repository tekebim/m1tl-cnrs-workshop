<?php
$_ENV["RELATIVE_PATH"] = "../";
$_ENV["RELATIVE_PATH_COMMON"] = "../common/";

$config = file_get_contents("./config/project.json");

// If config json file found
if ($config !== false) {
    // Try to decode the json file
    $configJson = json_decode($config, true);
    // If configuration file is json
    if ($configJson !== null) {
        foreach ($configJson as $config => $cfg) {
            define("_SITE_NAME_", $cfg['siteTitle']);
        }
    } else {
        initDefaultCfg();
    }
} else {
    initDefaultCfg();
}

function initDefaultCfg()
{
    define("_SITE_NAME_", "Website");
}

<?php
$_ENV["RELATIVE_PATH"] = "../";
$_ENV["RELATIVE_PATH_COMMON"] = "../common/";

function loadConfig()
{
    $config = file_get_contents(__DIR__ . "/config/project.json");
    // If config json file found
    if ($config !== false) {
        // Try to decode the json file
        $configJson = json_decode(stripslashes($config), true);
        // If configuration file is json
        if ($configJson !== null) {
            foreach ($configJson as $config => $cfg) {
                define("_PROJECT_SLUG_", $cfg['projectSlug']);
                define("_PROJECT_NAME_", $cfg['projectName']);
                define("_PROJECT_COLOR_PRIMARY_", $cfg['projectColorPrimary']);
                define("_PROJECT_COLOR_SECONDARY_", $cfg['projectColorSecondary']);
            }
        } else {
            initDefaultCfg();
        }
    } else {
        initDefaultCfg();
    }
}

function initDefaultCfg()
{
    define("_PROJECT_SLUG_", "untitled");
    define("_PROJECT_NAME_", "Project untitled");
    define("_PROJECT_COLOR_PRIMARY_", "#2f3373");
    define("_PROJECT_COLOR_SECONDARY_", "#c44343");
}


loadConfig();

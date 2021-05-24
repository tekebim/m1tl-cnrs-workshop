<?php
define('ROOT_DIR', realpath(__DIR__));

/*
 * Load configuration json file
 */
function loadConfig()
{
    $config = file_get_contents(__DIR__ . "/config/project.json");
    // If config json file found
    if ($config !== false) {
        // Try to decode the json file
        $configJson = json_decode($config, true);
        // If configuration file is json
        if ($configJson !== null) {
            foreach ($configJson as $config => $cfg) {
                define("_PROJECT_SLUG_", $cfg['projectSlug']);
                define("_PROJECT_NAME_", $cfg['projectName']);
                define("_PROJECT_COLOR_PRIMARY_", $cfg['colors']['projectColorPrimary']);
                define("_PROJECT_COLOR_SECONDARY_", $cfg['colors']['projectColorSecondary']);
                define("_PROJECT_COLOR_BODY_TEXT_", $cfg['colors']['projectColorBodyText']);
                define("_PROJECT_COLOR_BODY_BACKGROUND_", $cfg['colors']['projectColorBodyBackground']);
                define("_PROJECT_COLOR_HEADER_TEXT_", $cfg['colors']['projectColorHeaderText']);
                define("_PROJECT_COLOR_HEADER_BACKGROUND_", $cfg['colors']['projectColorHeaderBackground']);
            }
        } else {
            initDefaultCfg();
        }
    } else {
        initDefaultCfg();
    }
}

/*
 * Create default config value
 */
function initDefaultCfg()
{
    define("_PROJECT_SLUG_", "untitled");
    define("_PROJECT_NAME_", "Project untitled");
    define("_PROJECT_COLOR_PRIMARY_", "#2f3373");
    define("_PROJECT_COLOR_SECONDARY_", "#c44343");
    define("_PROJECT_BODY_TEXT_COLOR_", "#2f3373");
    define("_PROJECT_BODY_BACKGROUND_COLOR_", "#FFFFFF");
    define("_PROJECT_HEADER_TEXT_COLOR_", "#2f3373");
    define("_PROJECT_HEADER_BACKGROUND_COLOR_", "#FFFFFF");
}

loadConfig();

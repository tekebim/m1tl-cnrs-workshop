<?php

class ProjectConfig
{
    public $template;
    public $fileConfig;
    public $fileConfigPath;

    public function __construct()
    {
        // Configure variables and require
        $need_auth = true;
        require_once("./environnement.php");
        require_once($_ENV["RELATIVE_PATH"] . "common/init.php");
        // Load the template associated and pass param from global environnement
        $this->template = $twig->render('admin/project-configuration.twig', array(
            "projectSlug" => _PROJECT_SLUG_,
            "projectName" => _PROJECT_NAME_,
            "projectColorPrimary" => _PROJECT_COLOR_PRIMARY_,
            "projectColorSecondary" => _PROJECT_COLOR_SECONDARY_));
        // Initialize
        $this->fileConfig = "./config/project.json";
        $this->fileConfigPath = file_get_contents($this->fileConfig);
        $this->init();
    }

    /**
     * Function init
     */
    public function init()
    {
        // Check if post from update config form
        if (isset($_POST['updateProjectConfig'])) {
            $this->updateConfig();
        } else {
            // By default render form
            $this->renderForm();
        }
    }

    /**
     * Function to render the default form
     */
    public function renderForm()
    {
        echo '<main id="page-admin-configuration">';
        echo $this->template;
        echo '</main>';
    }

    /**
     * Function to update the project config
     */
    public function updateConfig()
    {
        // If post value missing
        if (!isset($_POST['project-slug']) || !isset($_POST['project-name']) || !isset($_POST['project-color-primary']) || !isset($_POST['project-color-secondary'])) {
            echo 'Required missing values';
            return;
        }

        // If config file exist
        if ($this->fileConfigPath !== false) {
            $configJson = json_decode(stripslashes($this->fileConfigPath), true);
            // If configuration file is json
            if ($configJson !== null) {
                // If trying to rename the slug
                if ($configJson['config']['projectSlug'] !== $_POST['project-slug']) {
                    // We must check that directory is unique
                    if ($this->checkIsDir($_POST['project-slug'])) {
                        echo 'A directory already exist with this slug. Please rename the project with another unique name.';
                        return;
                    }
                }

                $configJson['config']['projectSlug'] = htmlspecialchars($_POST['project-slug']);
                $configJson['config']['projectName'] = htmlspecialchars($_POST['project-name']);
                $configJson['config']['projectColorPrimary'] = htmlspecialchars($_POST['project-color-primary']);
                $configJson['config']['projectColorPrimaryDarken'] = $this->adjustBrightness(htmlspecialchars($_POST['project-color-primary']), -0.2);
                $configJson['config']['projectColorSecondary'] = htmlspecialchars($_POST['project-color-secondary']);
                $configJson['config']['projectColorSecondaryDarken'] = $this->adjustBrightness(htmlspecialchars($_POST['project-color-secondary']), -0.2);

                // Save modifications to the json file
                file_put_contents($this->fileConfig, json_encode($configJson));
            }
        }
        header('Location: ' . $_SERVER['PHP_SELF']);
    }

    /**
     * Function to check if directory by name already exist
     */
    public function checkIsDir(string $directory): bool
    {
        return is_dir('./../' . $directory);
    }

    /**
     * Increases or decreases the brightness of a color by a percentage of the current brightness.
     *
     * @param string $hexCode Supported formats: `#FFF`, `#FFFFFF`, `FFF`, `FFFFFF`
     * @param float $adjustPercent A number between -1 and 1. E.g. 0.3 = 30% lighter; -0.4 = 40% darker.
     *
     * @return  string
     *
     * @author  maliayas
     */
    public function adjustBrightness($hexCode, $adjustPercent)
    {
        $hexCode = ltrim($hexCode, '#');

        if (strlen($hexCode) == 3) {
            $hexCode = $hexCode[0] . $hexCode[0] . $hexCode[1] . $hexCode[1] . $hexCode[2] . $hexCode[2];
        }

        $hexCode = array_map('hexdec', str_split($hexCode, 2));

        foreach ($hexCode as & $color) {
            $adjustableLimit = $adjustPercent < 0 ? $color : 255 - $color;
            $adjustAmount = ceil($adjustableLimit * $adjustPercent);

            $color = str_pad(dechex($color + $adjustAmount), 2, '0', STR_PAD_LEFT);
        }

        return '#' . implode($hexCode);
    }
}

$config = new ProjectConfig();

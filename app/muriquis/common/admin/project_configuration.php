<?php

class ProjectConfig
{
    public $fileConfig;
    public $fileConfigPath;
    public $defaultFileConfig;
    public $defaultFileConfigPath;
    public $fileCSSColors;
    public $twig;

    public function __construct()
    {
        // Configure variables and require
        $need_auth = true;
        require_once(ROOT_DIR . "/common/init.php");
        // Enable twig to be used in functions
        $this->twig = $twig;
        // Initialize
        $this->fileCSSColors = ROOT_DIR . "/assets/css/color_local.css";
        $this->defaultFileConfig = ROOT_DIR . "/config/default.json";
        $this->fileConfig = ROOT_DIR . "/config/project.json";
        $this->defaultFileConfigPath = file_get_contents($this->defaultFileConfig);
        $this->fileConfigPath = file_get_contents($this->fileConfig);
        $this->init();
    }

    /**
     * Function init
     */
    public function init()
    {
        if (isset($_GET['edition'])) {
            // If page settings
            if ($_GET['edition'] === 'settings') {
                // Check if post from update config form
                if (isset($_POST['updateProjectConfig'])) {
                    $this->updateConfig();
                } // Check if need to reset config
                elseif (isset($_POST['resetDefaultConfig'])) {
                    $this->resetConfig();
                } else {
                    // By default render form
                    $this->renderSettingsForm();
                }
            } // If page editions
            elseif ($_GET['edition'] === 'pages') {
                $this->renderPagesForm();
            } else {
                header('Location: ' . $_SERVER['PHP_SELF'] . '?edition=settings');
                die();
            }
        } else {
            header('Location: ' . $_SERVER['PHP_SELF'] . '?edition=settings');
            die();
        }
    }

    /**
     * Function to render the settings form (default form)
     */
    public function renderSettingsForm()
    {
        echo $this->twig->render('admin/project_config_settings.twig', array(
            "projectSlug" => _PROJECT_SLUG_,
            "projectName" => _PROJECT_NAME_,
            "projectColorPrimary" => _PROJECT_COLOR_PRIMARY_,
            "projectColorSecondary" => _PROJECT_COLOR_SECONDARY_,
            "projectColorBodyText" => _PROJECT_COLOR_BODY_TEXT_,
            "projectColorBodyBackground" => _PROJECT_COLOR_BODY_BACKGROUND_,
            "projectColorHeaderText" => _PROJECT_COLOR_HEADER_TEXT_,
            "projectColorHeaderBackground" => _PROJECT_COLOR_HEADER_BACKGROUND_,
        ));
    }

    /**
     * Function to render the pages edition form
     */
    public function renderPagesForm()
    {
        $pages = $this->loadConfigPages();
        echo $this->twig->render('admin/project_config_pages.twig', array(
            "projectSlug" => _PROJECT_SLUG_,
            "pages" => $pages
        ));
    }

    /**
     * Function to render the pages edition form
     */
    public function loadConfigPages()
    {
        $data = [];
        // If config file exist
        if ($this->fileConfigPath !== false) {
            $configJson = json_decode($this->fileConfigPath, true);
            // If configuration file is json
            if ($configJson !== null) {
                // If trying to rename the slug
                if ($configJson['pages']) {
                    $pages = $configJson['pages'];
                    foreach ($pages as $page => $pageConfig) {
                        $data[] = $pageConfig;
                    }
                }
            }
        }
        return $data;
    }

    /**
     * Function to update the project config
     */
    public function updateConfig()
    {
        // If post value missing
        if (!isset($_POST['project-slug']) || !isset($_POST['project-name']) || !isset($_POST['project-color-primary']) || !isset($_POST['project-color-secondary']) || !isset($_POST['project-color-header-text']) || !isset($_POST['project-color-header-background']) || !isset($_POST['project-color-body-text']) || !isset($_POST['project-color-body-background'])) {
            echo 'Required missing values';
            return;
        }

        // If config file exist
        if ($this->fileConfigPath !== false) {
            $configJson = json_decode($this->fileConfigPath, true);
            // If configuration file is json
            if ($configJson !== null) {
                // If trying to rename the slug
                if ($configJson['config']['projectSlug'] !== $_POST['project-slug']) {
                    // We must check that directory is unique
                    if ($this->checkIsDir($_POST['project-slug'])) {
                        echo 'A directory already exist with this slug. Please rename the project with another unique name.';
                        return;
                    }
                    $currentDir = $configJson['config']['projectSlug'];
                    $updateDir = $_POST['project-slug'];
                } else {
                    // By default no need to update directory name
                    $updateDir = false;
                }

                $newColorPrimary = htmlspecialchars($_POST['project-color-primary']);
                $newColorSecondary = htmlspecialchars($_POST['project-color-secondary']);
                $newColorPrimaryDarken = $this->adjustBrightness(htmlspecialchars($_POST['project-color-primary']), -0.2);
                $newColorPrimaryLighten = $this->adjustBrightness(htmlspecialchars($_POST['project-color-primary']), +0.5);
                $newColorSecondaryDarken = $this->adjustBrightness(htmlspecialchars($_POST['project-color-secondary']), -0.2);
                $newColorSecondaryLighten = $this->adjustBrightness(htmlspecialchars($_POST['project-color-secondary']), +0.5);
                $newColorBodyText = htmlspecialchars($_POST['project-color-body-text']);
                $newColorHeaderText = htmlspecialchars($_POST['project-color-header-text']);
                $newColorBodyBackground = htmlspecialchars($_POST['project-color-body-background']);
                $newColorHeaderBackground = htmlspecialchars($_POST['project-color-header-background']);

                // Colors primary and secondary must be different
                if ($newColorPrimary === $newColorSecondary) {
                    echo 'You must choose two differents colors.';
                    return;
                }

                $colors = [
                    'currentColorPrimary' => $configJson['config']['colors']['projectColorPrimary'],
                    'currentColorPrimaryDarken' => $configJson['config']['colors']['projectColorPrimaryDarken'],
                    'currentColorPrimaryLighten' => $configJson['config']['colors']['projectColorPrimaryLighten'],
                    'currentColorSecondary' => $configJson['config']['colors']['projectColorSecondary'],
                    'currentColorSecondaryDarken' => $configJson['config']['colors']['projectColorSecondaryDarken'],
                    'currentColorSecondaryLighten' => $configJson['config']['colors']['projectColorSecondaryLighten'],
                    'currentColorBodyText' => $configJson['config']['colors']['projectColorBodyText'],
                    'currentColorHeaderText' => $configJson['config']['colors']['projectColorHeaderText'],
                    'currentColorBodyBackground' => $configJson['config']['colors']['projectColorBodyBackground'],
                    'currentColorHeaderBackground' => $configJson['config']['colors']['projectColorHeaderBackground'],
                    'newColorPrimary' => $newColorPrimary,
                    'newColorPrimaryDarken' => $newColorPrimaryDarken,
                    'newColorPrimaryLighten' => $newColorPrimaryLighten,
                    'newColorSecondary' => $newColorSecondary,
                    'newColorSecondaryDarken' => $newColorSecondaryDarken,
                    'newColorSecondaryLighten' => $newColorSecondaryLighten,
                    'newColorBodyText' => $newColorBodyText,
                    'newColorHeaderText' => $newColorHeaderText,
                    'newColorBodyBackground' => $newColorBodyBackground,
                    'newColorHeaderBackground' => $newColorHeaderBackground,
                ];

                // Update local variable in CSS
                $this->updateCSSFile($colors);

                $configJson['config']['projectSlug'] = htmlspecialchars($_POST['project-slug']);
                $configJson['config']['projectName'] = htmlspecialchars($_POST['project-name']);
                $configJson['config']['colors']['projectColorPrimary'] = $newColorPrimary;
                $configJson['config']['colors']['projectColorPrimaryDarken'] = $newColorPrimaryDarken;
                $configJson['config']['colors']['projectColorPrimaryLighten'] = $newColorPrimaryLighten;
                $configJson['config']['colors']['projectColorSecondary'] = $newColorSecondary;
                $configJson['config']['colors']['projectColorSecondaryDarken'] = $newColorSecondaryDarken;
                $configJson['config']['colors']['projectColorSecondaryLighten'] = $newColorSecondaryLighten;
                $configJson['config']['colors']['projectColorBodyText'] = $newColorBodyText;
                $configJson['config']['colors']['projectColorHeaderText'] = $newColorHeaderText;
                $configJson['config']['colors']['projectColorBodyBackground'] = $newColorBodyBackground;
                $configJson['config']['colors']['projectColorHeaderBackground'] = $newColorHeaderBackground;

                // Database last update date
                if (isset($_POST['project-bdd-last-update'])) {
                    $dbLastUpdate = htmlspecialchars($_POST['project-bdd-last-update']);
                    $configJson['config']['lastUpdate'] = $dbLastUpdate;
                }

                // Footer text content
                if (isset($_POST['project-footer-text'])) {
                    $footerText = htmlspecialchars($_POST['project-footer-text']);
                    $configJson['blocks']['footer']['content']  = $footerText;
                }

                // Save modifications to the json file
                file_put_contents($this->fileConfig, json_encode($configJson, JSON_HEX_QUOT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));

                // If projet slug name need to be update
                if ($updateDir) {
                    rename('./../' . $currentDir, './../' . $updateDir);
                    $currentPath = $_SERVER['PHP_SELF'];
                    $newPath = str_replace($currentDir, $updateDir, $currentPath);
                    header('Location: ' . $newPath);
                    die();
                }
            }
        }
        header('Location: ' . $_SERVER['PHP_SELF']);
        die();
    }

    /**
     * Function to reset default config
     */
    public function resetConfig()
    {
        // If default config file exist
        if (($this->defaultFileConfig !== false) && ($this->fileConfig !== false)) {
            var_dump('can reset');
            die();
        }
        header('Location: ' . $_SERVER['PHP_SELF']);
        die();

    }

    /**
     * Function to update CSS global var
     */
    public function updateCSSFile(array $colors)
    {
        // Open the CSS file
        $cssString = file_get_contents($this->fileCSSColors);
        $cssString = str_replace('--color-primary: ' . $colors['currentColorPrimary'], '--color-primary: ' . $colors['newColorPrimary'], $cssString);
        $cssString = str_replace('--color-primary-darken: ' . $colors['currentColorPrimaryDarken'], '--color-primary-darken: ' . $colors['newColorPrimaryDarken'], $cssString);
        $cssString = str_replace('--color-primary-lighten: ' . $colors['currentColorPrimaryLighten'], '--color-primary-lighten: ' . $colors['newColorPrimaryLighten'], $cssString);
        $cssString = str_replace('--color-secondary: ' . $colors['currentColorSecondary'], '--color-secondary: ' . $colors['newColorSecondary'], $cssString);
        $cssString = str_replace('--color-secondary-darken: ' . $colors['currentColorSecondaryDarken'], '--color-secondary-darken: ' . $colors['newColorSecondaryDarken'], $cssString);
        $cssString = str_replace('--color-secondary-lighten: ' . $colors['currentColorSecondaryLighten'], '--color-secondary-lighten: ' . $colors['newColorSecondaryLighten'], $cssString);
        $cssString = str_replace('--body-text-color: ' . $colors['currentColorBodyText'], '--body-text-color: ' . $colors['newColorBodyText'], $cssString);
        $cssString = str_replace('--body-background-color: ' . $colors['currentColorBodyBackground'], '--body-background-color: ' . $colors['newColorBodyBackground'], $cssString);
        $cssString = str_replace('--header-background-color: ' . $colors['currentColorHeaderBackground'], '--header-background-color: ' . $colors['newColorHeaderBackground'], $cssString);
        $cssString = str_replace('--header-text-color: ' . $colors['currentColorHeaderText'], '--header-text-color: ' . $colors['newColorHeaderText'], $cssString);
        // Save changes on file
        file_put_contents($this->fileCSSColors, $cssString);
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

<?php

require_once(ROOT_DIR . "/common/init.php");

if (isset($_POST['updatePageContent'])) {
    // Load project config
    $fileConfigPath = ROOT_DIR . "/config/project.json";
    $fileConfig = file_get_contents($fileConfigPath);
    // If config file exist
    if ($fileConfig !== false) {
        $configJson = json_decode(stripslashes($fileConfig), true);
        // If configuration file is json
        if ($configJson !== null) {
            $pages = $configJson['pages'];
            if ($pages) {
                for ($i = 0; $i < count($pages); $i++) {
                    $subpages = $pages[$i]['subpages'];
                    if ($subpages) {
                        for ($s = 0; $s < count($pages[$i]['subpages']); $s++) {
                            if ((int)$subpages[$s]['id'] === (int)$_POST['page-id']) {
                                $configJson['pages'][$i]['subpages'][$s]['isActive'] = $_POST['page-is-active'] === 'on' ? true : false;
                                $configJson['pages'][$i]['subpages'][$s]['title'] = $_POST['page-title'];
                                $configJson['pages'][$i]['subpages'][$s]['template'] = $_POST['page-template'];
                                $configJson['pages'][$i]['subpages'][$s]['content'] = $_POST['page-content'];
                                file_put_contents($fileConfigPath, json_encode($configJson));
                                break;
                            }
                        }
                    }
                }
            }
        }
    }
    header('Location: ./project_configuration.php?edition=pages');
} else {
    // Redirect to index
    header('Location: ../index.php');
    die();
}

<?php

require_once(ROOT_DIR . "/common/init.php");

$data = $_POST['data'];
/**
 * Function to render the pages edition form
 */
function loadConfigPage()
{
    $data = [];
    // If config file exist
    if ($this->fileConfigPath !== false) {
        $configJson = json_decode(stripslashes($this->fileConfigPath), true);
        // If configuration file is json
        if ($configJson !== null) {
            // If trying to rename the slug
            if ($configJson['pages']) {
                $pages = $configJson['pages'];
                foreach ($pages as $page => $pageCconfig) {
                    $data[] = $pageCconfig;
                }
            }
        }
    }
    return $data;
}

// echo $twig->render('admin/form-page-content.twig', array("data" => $_POST['data']));

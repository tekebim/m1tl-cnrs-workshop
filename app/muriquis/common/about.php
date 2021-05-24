<?php
$ajax = false;
$need_auth = false;
$want_menu = true;
require_once("./common/init.php");

if (isset($_GET["pid"])) {
    $pid = $_GET["pid"];
    $pageContent = getAboutContent($pid);

    echo '<main id="page-about">';
    echo $twig->render('pages/about.twig', array(
        "content" => $pageContent
    ));
    echo '</main>';
} else {
    header('Location: index.php');
}

/*
 * Function to get items menu element
 */
function getAboutContent($id)
{
    // Load project config
    $fileConfigPath = ROOT_DIR . "/config/project.json";
    $fileConfig = file_get_contents($fileConfigPath);

    $pageData = [];
    $defaultId = null;
    // If config file exist
    if ($fileConfig !== false) {
        $configJson = json_decode(stripslashes($fileConfig), true);
        // If configuration file is json
        if ($configJson !== null) {
            $pages = $configJson['pages'];
            if ($pages) {
                foreach ($pages as $page) {
                    // Actual page config
                    if ($page['url'] === 'about.php') {
                        foreach ($page['subpages'] as $subpage) {
                            if ((int)$subpage['id'] === (int)$id) {
                                $pageData = $subpage;
                            } else {
                                $defaultId = $subpage['id'];
                            }
                        }
                    }
                }
            }
            // If no content found for this id
            if (count($pageData) === 0) {
                // Redirect to index
                header('Location: ' . $_SERVER['PHP_SELF'] . '?pid=' . $defaultId);
                die();
            }
            return $pageData;
        }
    }
}

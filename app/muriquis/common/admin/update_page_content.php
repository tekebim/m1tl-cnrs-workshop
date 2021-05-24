<?php

require_once(ROOT_DIR . "/common/init.php");

class PageUpdate
{
    public $fileConfig;
    public $fileConfigPath;
    public $pageId;

    public function __construct()
    {
        $need_auth = true;
        require_once(ROOT_DIR . "/common/init.php");
        // Enable twig to be used in functions
        $this->twig = $twig;
        // Initialize
        $this->fileConfigPath = ROOT_DIR . "/config/project.json";
        $this->fileConfig = file_get_contents($this->fileConfigPath);
        $this->init();
    }

    public function init()
    {
        // Check if from update form
        if (isset($_POST['updatePageContent']) && isset($_POST['page-id'])) {
            // Assign page id
            $this->pageId = (int)$_POST['page-id'];
            $this->updateContentPage('update');
            header('Location: ./project_configuration.php?edition=pages');
            die();
        } // Check if from button delete on edition form
        elseif (isset($_POST['removePage']) && isset($_POST['page-id'])) {
            // Assign page id
            $this->pageId = (int)$_POST['page-id'];
            $this->updateContentPage('delete');
            header('Location: ./project_configuration.php?edition=pages');
            die();
        } else {
            $this->redirectToIndex();
        }
    }

    public function redirectToIndex()
    {
        header('Location: ../index.php');
        die();
    }

    public function updateContentPage($action)
    {
        if ($this->fileConfig !== false) {
            $configJson = json_decode(stripslashes($this->fileConfig), true);
            // If configuration file json found
            if ($configJson !== null) {
                $pages = $configJson['pages'];
                if ($pages) {
                    // Loop on each pages
                    for ($i = 0; $i < count($pages); $i++) {
                        $subpages = $pages[$i]['subpages'];
                        // Loop on each subpages
                        if ($subpages) {
                            for ($s = 0; $s < count($pages[$i]['subpages']); $s++) {
                                if ((int)$subpages[$s]['id'] === $this->pageId) {
                                    if ($action === 'delete') {
                                        // Delete element
                                        unset($configJson['pages'][$i]['subpages'][$s]);
                                    } else if ($action === 'update') {
                                        $configJson['pages'][$i]['subpages'][$s]['isActive'] = $_POST['page-is-active'] === 'on' ? true : false;
                                        $configJson['pages'][$i]['subpages'][$s]['title'] = $_POST['page-title'];
                                        $configJson['pages'][$i]['subpages'][$s]['template'] = $_POST['page-template'];
                                        $configJson['pages'][$i]['subpages'][$s]['content'] = $_POST['page-content'];
                                    }
                                    // Save the file
                                    file_put_contents($this->fileConfigPath, json_encode($configJson));
                                    break;
                                }
                            }
                        }
                    }
                }
            }
        }
    }
}

$page = new PageUpdate();

/*
if (isset($_POST['updatePageContent'])) {
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
} elseif (isset($_POST['removePage'])) {
    var_dump('removePage');
    updateContentPage('delete', (int)$_POST['page-id']);
    die();
} else {
// Redirect to index
    header('Location: ../index.php');
    die();
}
*/


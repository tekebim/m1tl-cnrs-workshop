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

    /*
     * Function to redirect on index page
     */
    public function redirectToIndex()
    {
        header('Location: ../index.php');
        die();
    }

    /*
     * Function update content page
     */
    public function updateContentPage($action)
    {
        if ($this->fileConfig !== false) {
            $configJson = json_decode($this->fileConfig, true);
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
                                if ((int)$subpages[$s]['id'] === (int)$this->pageId) {
                                    if ($action === 'delete') {
                                        unset($configJson['pages'][$i]['subpages'][$s]);
                                        // Save the file
                                        file_put_contents($this->fileConfigPath, json_encode($configJson, JSON_HEX_QUOT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
                                        break;
                                    } else if ($action === 'update') {
                                        $configJson['pages'][$i]['subpages'][$s]['isActive'] = $_POST['page-is-active'] === 'on' ? true : false;
                                        $configJson['pages'][$i]['subpages'][$s]['title'] = $_POST['page-title'];
                                        $configJson['pages'][$i]['subpages'][$s]['template'] = $_POST['page-template'];
                                        $configJson['pages'][$i]['subpages'][$s]['content'] = $_POST['page-content'];
                                        // Save the file
                                        file_put_contents($this->fileConfigPath, json_encode($configJson, JSON_HEX_QUOT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
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
}

$page = new PageUpdate();

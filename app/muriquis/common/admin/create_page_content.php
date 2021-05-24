<?php

class PageCreate
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
        if (isset($_POST['createPageContent'])) {
            // Detect if parent page id is defined
            if (isset($_POST['page-parent-id']) && $_POST['page-parent-id'] !== '') {
                // Assign page id
                $this->pageId = (int)$_POST['page-parent-id'];
                // Create the subpage
                $this->createPage('subpage');
            } else {
                // Create new main page
                $this->createPage('page');
            }
            header('Location: ./project_configuration.php?edition=pages');
            die();
        } // Check if from button on edition form
        else {
            $this->redirectToIndex();
        }
    }

    public function redirectToIndex()
    {
        header('Location: ../index.php');
        die();
    }

    public function createPage($action)
    {
        if ($this->fileConfig !== false) {
            $configJson = json_decode($this->fileConfig, true);
            // If configuration file json found
            if ($configJson !== null) {
                $pages = $configJson['pages'];
                if ($pages) {
                    for ($i = 0; $i < count($pages); $i++) {
                        if ($action === 'subpage') {
                            if ((int)$pages[$i]['id'] === $this->pageId) {
                                $subpages = $pages[$i]['subpages'];
                                $newPageIndex = count($subpages);
                                $newPageID = uniqid();

                                $configJson['pages'][$i]['subpages'][$newPageIndex]['id'] = $newPageID;
                                $configJson['pages'][$i]['subpages'][$newPageIndex]['isActive'] = $_POST['page-is-active'] === 'on' ? true : false;
                                $configJson['pages'][$i]['subpages'][$newPageIndex]['title'] = $_POST['page-title'];
                                $configJson['pages'][$i]['subpages'][$newPageIndex]['template'] = $_POST['page-template'];
                                $configJson['pages'][$i]['subpages'][$newPageIndex]['image'] = "cover-page-" . $newPageID . ".jpg";
                                $configJson['pages'][$i]['subpages'][$newPageIndex]['content'] = $_POST['page-content'];

                                // Save the file
                                file_put_contents($this->fileConfigPath, json_encode($configJson, JSON_HEX_QUOT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
                                break;
                            }
                        } elseif ($action === 'page') {
                            $newPageID = uniqid();
                            $configJson['pages'][] = [
                                'id' => $newPageID,
                                'title' => $_POST['page-title'],
                                'url' => $_POST['page-title'] . 'php',
                                'isActive' => $_POST['page-is-active'] === 'on' ? true : false,
                                'enableSubpages' => $_POST['page-enableSubPages'] === 'on' ? true : false
                            ];

                            // Save the file
                            file_put_contents($this->fileConfigPath, json_encode($configJson, JSON_HEX_QUOT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
                            break;
                        }
                    }
                    /*
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
                                    file_put_contents($this->fileConfigPath, json_encode($configJson, JSON_HEX_QUOT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
                                    break;
                                }
                            }
                        }
                    }
                    */
                }
            }
        }
    }

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

$page = new PageCreate();

<?php

defined('ABSPATH') || exit;

class SettingsPageController
{

    private $configuration;

    function __construct()
    {
        $this->loadIncludes();
        $this->configuration = new Configuration();
    }

    /**
     * Loading plugin php files
     *
     * @return void
     */
    function loadIncludes()
    {
        require_once(WEB_SCRAPING_PLUGIN_DIR . '/model/settings.php');
    }

    /**
     * Gets the Settings page view
     *
     * @return void
     */
    function getSettingsPageView()
    {
        // Sub-level Settings menu
        if ($this->configuration->getLanguage() == 'english') {
            add_submenu_page(
                'web_scraping',
                'Settings',
                'Settings',
                'manage_options',
                'web-scraping-settings',
                array($this, 'loadSettingsPageView')
            );
        } else {
            add_submenu_page(
                'web_scraping',
                'Configuración',
                'Configuración',
                'manage_options',
                'web-scraping-settings',
                array($this, 'loadSettingsPageView')
            );
        }
    }

    /**
     * Loads the Settings page view
     *
     * @return void
     */
    function loadSettingsPageView()
    {
        require_once(WEB_SCRAPING_PLUGIN_DIR . '/views/settingsPageView.php');
        getSettingsPage($this->configuration->getLanguage());
    }
}

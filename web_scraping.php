<?php
/*
Plugin Name: Web Scraping
Description: Hace scraping de la web que ingreses.
Author: REDDevs
Author URI: https://reddevs.net/
Text Domain: web-scraping
Version: 1.0.0

Copyright 2022 - REDDevs
*/

defined('ABSPATH') || exit;

class WebScraping
{

    private $settingsPageController;

    function __construct()
    {
        $this->defineConstants();
        $this->loadIncludes();
        $this->webScrapingEnqueueScripts();
        $this->settingsPageController = new SettingsPageController();

        $this->initHooks();
    }

    /**
     * Define plugin constants
     *
     * @return void
     */
    function defineConstants()
    {
        define('WEB_SCRAPING_PLUGIN_URL', plugin_dir_url(__FILE__));
        define('WEB_SCRAPING_PLUGIN_DIR', plugin_dir_path(__FILE__));
        define('WEB_SCRAPING_PLUGIN_FILE', __FILE__);
        define('WEB_SCRAPING_PLUGIN_BASENAME', plugin_basename(__FILE__));
    }

    /**
     * Loading plugin php files
     *
     * @return void
     */
    function loadIncludes()
    {
        require_once(WEB_SCRAPING_PLUGIN_DIR . '/inc/scraping_page.php');
        require_once(WEB_SCRAPING_PLUGIN_DIR . '/inc/simple_html_dom/simple_html_dom.php');
        require_once(WEB_SCRAPING_PLUGIN_DIR . '/inc/create_post.php');
        require_once(WEB_SCRAPING_PLUGIN_DIR . '/controller/settingsPageController.php');
    }

    /**
     * Initialize plugin hooks
     *
     * @return void
     */
    function initHooks()
    {
        add_action('admin_enqueue_scripts', array($this, 'webScrapingEnqueueScripts'));
        add_action('admin_menu', array($this, 'webScrapingAdminMenu'));
        register_activation_hook(__FILE__, array($this, 'myPluginActivate'));
        register_deactivation_hook(__FILE__, array($this, 'myPluginDeactivate'));
    }

    /**
     * Enqueue plugin scripts and styles
     *
     * @return void
     */
    function webScrapingEnqueueScripts()
    {
        global $pagenow;
        if (('admin.php' === $pagenow) && ('web-scraping-import' === $_GET['page'] || 'web-scraping-settings' === $_GET['page'])) {
            // CSS
            wp_enqueue_style('web-scraping-spinner', WEB_SCRAPING_PLUGIN_URL . 'assets/css/spinner.css');
            wp_enqueue_style('web-scraping', WEB_SCRAPING_PLUGIN_URL . 'assets/css/get_data_scraping.css');
            wp_enqueue_style('web-scraping-settings-page', WEB_SCRAPING_PLUGIN_URL . 'assets/css/settings.css');

            // JS
            wp_register_script('web-scraping-types', WEB_SCRAPING_PLUGIN_URL . 'assets/js/web_scraping_types.js', array('jquery'));
            wp_register_script('web-scraping', WEB_SCRAPING_PLUGIN_URL . 'assets/js/get_data_scraping.js', array('jquery'));
            wp_localize_script('web-scraping', 'my_ajax_object', array('ajax_url' => admin_url('admin-ajax.php')));
            wp_enqueue_script('web-scraping-types');
            wp_enqueue_script('web-scraping');
        }
    }

    /**
     * Add admin menu
     *
     * @return void
     */
    function webScrapingAdminMenu()
    {
        // Top-level Web Scraping menu
        add_menu_page(
            'Web Scraping',
            'Web Scraping',
            'web_scraping',
            'web_scraping',
            '',
            'data:image/svg+xml;base64,' . base64_encode(file_get_contents(WEB_SCRAPING_PLUGIN_URL . 'assets/img/logo.svg')),
        );

        // Sub-level Import menu
        if (get_option('wep_scraping_plugin_language') == 'english') {
            add_submenu_page(
                'web_scraping',
                'Import',
                'Import',
                'manage_options',
                'web-scraping-import',
                array($this, 'loadImportPageView')
            );
        } else {
            add_submenu_page(
                'web_scraping',
                'Importar',
                'Importar',
                'manage_options',
                'web-scraping-import',
                array($this, 'loadImportPageView')
            );
        }

        $this->settingsPageController->getSettingsPageView();
    }

    /**
     * Plugin activation
     *
     * @return void
     */
    function myPluginActivate()
    {
        if (!get_option('wep_scraping_plugin_language')) {
            if (substr(get_locale(), 0, 3) === 'es_') {
                update_option('wep_scraping_plugin_language', 'spanish');
            } else {
                update_option('wep_scraping_plugin_language', 'english');
            }
        }
    }

    /**
     * Plugin deactivation
     *
     * @return void
     */
    function myPluginDeactivate()
    {
        // TODO: Falta eliminar las opciones del plugin

    }

    /**
     * Loads the Import page view
     *
     * @return void
     */
    function loadImportPageView()
    {
        require_once(WEB_SCRAPING_PLUGIN_DIR . '/views/web_scraping_import_page.php');
    }
}

$webScraping = new WebScraping();

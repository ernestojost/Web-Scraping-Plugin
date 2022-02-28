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

/* 
 * Constants
 */
define( 'WEB_SCRAPING_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'WEB_SCRAPING_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'WEB_SCRAPING_PLUGIN_FILE', __FILE__ );
define( 'WEB_SCRAPING_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );

/*
 * Includes
 */
require_once( WEB_SCRAPING_PLUGIN_DIR . '/inc/scraping-page.php' );
require_once( WEB_SCRAPING_PLUGIN_DIR . '/inc/simple_html_dom/simple_html_dom.php' );
require_once( WEB_SCRAPING_PLUGIN_DIR . '/inc/create-post.php' );

/* Agrega los archivos de JS y CSS */
add_action( 'admin_enqueue_scripts', 'web_scraping_enqueue_scripts' );
function web_scraping_enqueue_scripts() {

    global $pagenow; 
    if ( ( 'admin.php' === $pagenow ) && ( 'web-scraping-import' === $_GET['page'] || 'web-scraping-settings' === $_GET['page'] ) ) { 
        // CSS
        wp_enqueue_style( 'web-scraping-spinner', WEB_SCRAPING_PLUGIN_URL . 'assets/css/spinner.css' );
        wp_enqueue_style( 'web-scraping', WEB_SCRAPING_PLUGIN_URL . 'assets/css/get_data_scraping.css' );
        wp_enqueue_style( 'web-scraping-settings-page', WEB_SCRAPING_PLUGIN_URL . 'assets/css/settings.css' );

        // JS
        wp_register_script( 'web-scraping-types', WEB_SCRAPING_PLUGIN_URL . 'assets/js/web_scraping_types.js', array('jquery') );
        wp_register_script( 'web-scraping', WEB_SCRAPING_PLUGIN_URL . 'assets/js/get_data_scraping.js', array('jquery') );
        wp_localize_script( 'web-scraping', 'my_ajax_object', array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );
        wp_enqueue_script( 'web-scraping-types' );
        wp_enqueue_script( 'web-scraping' );
    }

}

register_activation_hook( __FILE__, 'my_plugin_activate' );
function my_plugin_activate() {
    if ( ! get_option('wep_scraping_plugin_language') ) {
        if (substr(get_locale(), 0, 3) === 'es_') { 
            update_option('wep_scraping_plugin_language', 'spanish' );
        } else {
            update_option('wep_scraping_plugin_language', 'english' );
        }
    }
}


register_deactivation_hook( __FILE__, 'my_plugin_deactivate' );
function my_plugin_deactivate() {
    // Desactivacion
    $a = 1;
}



/* Agrega un elemento al submenu Posts de wordpress */
add_action( 'admin_menu', 'web_scraping_admin_menu' );

function web_scraping_admin_menu() {




    // Top-level Web Scraping menu
    add_menu_page(
        'Web Scraping',
        'Web Scraping',
        'web_scraping',
        'web_scraping',
        '',
        'data:image/svg+xml;base64,' . base64_encode(file_get_contents( WEB_SCRAPING_PLUGIN_URL . 'assets/img/logo.svg' )),
    );

    // Sub-level Import menu
    if (get_option('wep_scraping_plugin_language') == 'english') {
        add_submenu_page(
            'web_scraping', 
            'Import', 
            'Import', 
            'manage_options', 
            'web-scraping-import', 
            'web_scraping_import_page'
        );
    } else {
        add_submenu_page(
            'web_scraping', 
            'Importar', 
            'Importar', 
            'manage_options', 
            'web-scraping-import', 
            'web_scraping_import_page'
        );
    }

    // Sub-level Settings menu
    if (get_option('wep_scraping_plugin_language') == 'english') {
        add_submenu_page(
            'web_scraping', 
            'Settings', 
            'Settings', 
            'manage_options', 
            'web-scraping-settings', 
            'web_scraping_settings_page'
        );
    } else {
        add_submenu_page(
            'web_scraping', 
            'Configuración', 
            'Configuración', 
            'manage_options', 
            'web-scraping-settings', 
            'web_scraping_settings_page'
        );
    }


    function web_scraping_import_page() {
        require_once( WEB_SCRAPING_PLUGIN_DIR . '/views/web_scraping-import-page.php' );
    }

    function web_scraping_settings_page() {
        require_once( WEB_SCRAPING_PLUGIN_DIR . '/views/web_scraping-settings-page.php' );
    }
}


<?php

defined( 'ABSPATH' ) || exit;

if(isset($_POST['language'])) {
    update_option('wep_scraping_plugin_language', $_POST['language'] );
}
?>

<div id="web-scraping-settings-page">
    <h2><?php echo (get_option('wep_scraping_plugin_language') == 'english') ? 'SETTINGS': 'CONFIGURACIÓN';?></h2>
    <hr>
    <div id="web-scraping-settings-content">
        <form method="post" action="#">
            <div>
                <label><?php echo (get_option('wep_scraping_plugin_language') == 'english') ? 'Language': 'Idioma';?>:</label>
                <select name="language">
                    <option value="english" <?php echo (get_option('wep_scraping_plugin_language') == 'english') ? 'selected': '';?>>
                        <?php echo (get_option('wep_scraping_plugin_language') == 'english') ? 'English': 'Inglés';?>
                    </option>
                    <option value="spanish" <?php echo (get_option('wep_scraping_plugin_language') == 'spanish') ? 'selected': '';?>>
                        <?php echo (get_option('wep_scraping_plugin_language') == 'english') ? 'Spanish': 'Español';?>
                    </option>
                </select>
            </div>
            <input type="submit" value="<?php echo (get_option('wep_scraping_plugin_language') == 'english') ? 'Save': 'Guardar';?>">
        </form>
    </div>
</div>




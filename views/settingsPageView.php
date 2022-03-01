<?php

defined('ABSPATH') || exit;

/**
 * Returns the html of the Settings page
 *
 * @param string Language to be displayed
 * @return html 
 */
function getSettingsPage($language)
{ ?>
    <div id="web-scraping-settings-page">
        <h2><?= ($language == 'english') ? 'SETTINGS' : 'CONFIGURACIÓN' ?></h2>
        <hr>
        <div id="web-scraping-settings-content">
            <form method="post" action="#">
                <div>
                    <label><?= ($language == 'english') ? 'Language' : 'Idioma' ?>:</label>
                    <select name="language">
                        <option value="english" <?= ($language == 'english') ? 'selected' : '' ?>>
                            <?= ($language == 'english') ? 'English' : 'Inglés' ?>
                        </option>
                        <option value="spanish" <?= ($language == 'spanish') ? 'selected' : '' ?>>
                            <?= ($language == 'english') ? 'Spanish' : 'Español' ?>
                        </option>
                    </select>
                </div>
                <input type="submit" value="<?= ($language == 'english') ? 'Save' : 'Guardar' ?>">
            </form>
        </div>
    </div>
<?php }

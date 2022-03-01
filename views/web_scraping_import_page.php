<?php

defined( 'ABSPATH' ) || exit;

$url = $_POST['url'];
$article_jquery = $_POST['article-jquery'];
$title_jquery = $_POST['title-jquery'];
$content_jquery = $_POST['content-jquery'];
?>

<div id="web-scraping-import-page">
    <p id="wep-scraping-plugin-language" style="display:none;"><?=get_option('wep_scraping_plugin_language')?></p>
    <div id="web-scraping-types-scraping">
        <h2><?php echo (get_option('wep_scraping_plugin_language') == 'english') ? 'IMPORT': 'IMPORTAR';?></h2>
        <hr>
        <div id="web-scraping-types-scraping-content">
            <h3><?php echo (get_option('wep_scraping_plugin_language') == 'english') ? 'Select type of scraping': 'Seleccione el tipo de scraping';?></h3>
            <form>
                <div id="type-blog-with-articles" class="web-scraping-type" article-type="blog-with-articles">
                    <div class="type-custom-scraping-img">
                        <img src="<?=WEB_SCRAPING_PLUGIN_URL.'/assets/img/scraping-types/blog-with-articles.svg'?>" alt="Blog with articles">
                    </div>
                    <div>
                        <input type="radio" name="type_of_scraping" value="Blog with articles">
                        <label><?php echo (get_option('wep_scraping_plugin_language') == 'english') ? 'Blog with articles': 'Blog con artículos';?></label><br>
                    </div>
                </div>
                <div id="type-custom-scraping" class="web-scraping-type" article-type="custom-scraping">
                    <div class="type-custom-scraping-img">
                        <img src="<?=WEB_SCRAPING_PLUGIN_URL.'/assets/img/scraping-types/custom-scraping.svg'?>" alt="Custom scraping">
                    </div>
                    <div>
                        <input type="radio" name="type_of_scraping" value="Custom scraping">
                        <label><?php echo (get_option('wep_scraping_plugin_language') == 'english') ? 'Custom scraping': 'Scraping personalizado';?></label><br>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <hr>
    <div id="web-scraping-import">
        <h3 id="web-scraping-type-selected-name"></h3>
        <p id="web-scraping-type-selected-description"></p>
        <hr>
        <div id="form-web-scraping" method="post">
            <label for="url"><?php echo (get_option('wep_scraping_plugin_language') == 'english') ? 'URL Blog': 'URL Blog';?>:</label>
            <input id="form-web-scraping-url" type="text" name="url" placeholder="<?php echo (get_option('wep_scraping_plugin_language') == 'english') ? 'Enter URL to scrape': 'Introduzca la URL a scrapear';?>" value="<?=$url?>">
            <div class="web-scraping-page-body">
                <div class="form-web-scraping-element">
                    <label><?php echo (get_option('wep_scraping_plugin_language') == 'english') ? 'Article jQuery': 'Artículo jQuery';?>:</label>
                    <input type="text" placeholder="<?php echo (get_option('wep_scraping_plugin_language') == 'english') ? 'Enter article jQuery': 'Ingrese el jQuery del artículo';?>" value="<?=$article_jquery?>">
                </div>
                <div class="web-scraping-page-content">
                    <div class="form-web-scraping-element">
                        <label><?php echo (get_option('wep_scraping_plugin_language') == 'english') ? 'Title jQuery': 'Título jQuery';?>:</label>
                        <input type="text" placeholder="<?php echo (get_option('wep_scraping_plugin_language') == 'english') ? 'Enter title jQuery': 'Introduzca el jQuery del título';?>" value="<?=$title_jquery?>">
                    </div>
                    <div class="form-web-scraping-element">
                        <label><?php echo (get_option('wep_scraping_plugin_language') == 'english') ? 'Content jQuery': 'Contenido jQuery';?>:</label>
                        <input type="text" placeholder="<?php echo (get_option('wep_scraping_plugin_language') == 'english') ? 'Enter content jQuery': 'Ingrese el jQuery del contenido';?>" value="<?=$content_jquery?>">
                    </div>
                </div>
            </div>
            <label for="reading-format"><?php echo (get_option('wep_scraping_plugin_language') == 'english') ? 'Reading format': 'Formato de lectura';?>:</label>
            <select name="reading-format" id="form-web-scraping-reading-format">
                <option value="plaintext"><?php echo (get_option('wep_scraping_plugin_language') == 'english') ? 'Plain Text': 'Texto sin formato';?></option>
                <option value="innertext" selected="selected"><?php echo (get_option('wep_scraping_plugin_language') == 'english') ? 'Inner Text': 'Texto interior';?></option>
                <option value="outertext"><?php echo (get_option('wep_scraping_plugin_language') == 'english') ? 'Outer Text': 'Texto exterior';?></option>
            </select>
            <div id="form-web-scraping-panel">
                <button class="submit" type="button"><?php echo (get_option('wep_scraping_plugin_language') == 'english') ? 'Search': 'Buscar';?></button>
                <p id="form-web-scraping-panel-notice">Hay un error</p>
            </div>
        </div>    
        <div id="web-scraping-data-obtained">
            <hr>
            <h3><?php echo (get_option('wep_scraping_plugin_language') == 'english') ? 'Data obtained': 'Datos obtenidos';?></h3>
            <hr>
            <div id="web-scraping-data-obtained-content">
                <div id="web-scraping-data-obtained-panel">
                    <div id="web-scraping-data-obtained-panel-format">
                        <p class="web-scraping-data-obtained-panel-title"><?php echo (get_option('wep_scraping_plugin_language') == 'english') ? 'Format': 'Formatear';?></p>
                        <button id="web-scraping-data-obtained-format-all" class="submit web-scraping-data-obtained-panel-button-secondary" type="button"><?php echo (get_option('wep_scraping_plugin_language') == 'english') ? 'Format all': 'Formatear todo';?></button>
                    </div>
                    <div id="web-scraping-data-obtained-panel-create-posts">
                        <p class="web-scraping-data-obtained-panel-title"><?php echo (get_option('wep_scraping_plugin_language') == 'english') ? 'Create posts': 'Crear posts';?></p>
                        <button id="web-scraping-data-obtained-create-all-posts" class="submit web-scraping-data-obtained-panel-button" type="button"><?php echo (get_option('wep_scraping_plugin_language') == 'english') ? 'Create all posts': 'Crear todos los posts';?></button>
                    </div>
                </div>
                <p id="web-scraping-data-obtained-notice"><?php echo (get_option('wep_scraping_plugin_language') == 'english') ? 'No data to show': 'No hay datos para mostrar';?></p>
                <img id="web-scraping-search-spinner" src="<?=WEB_SCRAPING_PLUGIN_URL.'/assets/img/axe.png'?>" alt="<?php echo (get_option('wep_scraping_plugin_language') == 'english') ? 'Searching': 'Buscando';?>">
                <div id="web-scraping-articles"></div>
            </div>
        </div>
    </div>
    
</div>




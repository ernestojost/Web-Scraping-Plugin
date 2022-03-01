<?php

defined( 'ABSPATH' ) || exit;

/**
 * Obtiene la cantidad de artículos de una web
 */
function get_quantity_articles(){  

    if (!isset($_POST['url']) || $_POST['url'] == '') {
        echo json_encode( array(
            "status" => "error",
            "message" => "No se ha proporcionado la URL"
        ));
        die;
    } else if (!isset($_POST['article']) || $_POST['article'] == '') {
        echo json_encode( array(
            "status" => "error",
            "message" => "No se ha proporcionado el selector de artículo"
        ));
        die;
    }

    $url = $_POST['url'];
    $article = $_POST['article'];

    // Create DOM from URL or file
    $html = file_get_html($url);

    $quantity = count($html->find($article));

    $url_articles = array();
    foreach($html->find($article) as $article) {
        array_push($url_articles, $article->href);
    }

    echo json_encode( array(
        "status" => "success",
        "quantity" => $quantity,
        "url_articles" => $url_articles
    ));
    die;
    
}
add_action('wp_ajax_get_quantity_articles', 'get_quantity_articles');
add_action('wp_ajax_nopriv_get_quantity_articles', 'get_quantity_articles');


/**
 * Obtiene un artículo de una web
 */
function get_all_articles(){  

    if (!isset($_POST['url_articles']) || $_POST['url_articles'] == '') {
        echo json_encode( array(
            "status" => "error",
            "message" => "No se ha proporcionado URL de artículos"
        ));
        die;
    } else if (!isset($_POST['title_jquery']) || $_POST['title_jquery'] == '') {
        echo json_encode( array(
            "status" => "error",
            "message" => "No se ha proporcionado el selector de título"
        ));
        die;
    } else if (!isset($_POST['content_jquery']) || $_POST['content_jquery'] == '') {
        echo json_encode( array(
            "status" => "error",
            "message" => "No se ha proporcionado el selector de contenido"
        ));
        die;
    } else if (!isset($_POST['reading_format']) || $_POST['reading_format'] == '') {
        echo json_encode( array(
            "status" => "error",
            "message" => "No se ha proporcionado el formato de lectura"
        ));
        die;
    }

    require_once( WEB_SCRAPING_PLUGIN_DIR . '/views/article_search.php' );

    $url_articles = $_POST['url_articles'];
    $title_jquery = $_POST['title_jquery'];
    $content_jquery = $_POST['content_jquery'];
    $reading_format = $_POST['reading_format'];

    $article_id = 1;
    foreach($url_articles as $url_article) {
        $htmlArticle = file_get_html($url_article);
        $title = $htmlArticle->find($title_jquery, 0)->plaintext;
        
        if ($reading_format == 'plaintext') {
            $content = $htmlArticle->find($content_jquery, 0)->plaintext;
        } else if ($reading_format == 'innertext') {
            $content = $htmlArticle->find($content_jquery, 0)->innertext;
        } else {
            $content = $htmlArticle->find($content_jquery, 0)->outertext;
        }

        $html_article = get_html_article($title, $content, $article_id);

        $articles[] = $html_article;
        $article_id++;
    }

    echo json_encode( array(
        "status" => "success",
        "articles" => $articles,
    ));
    die;
    
}
add_action('wp_ajax_get_all_articles', 'get_all_articles');
add_action('wp_ajax_nopriv_get_all_articles', 'get_all_articles');
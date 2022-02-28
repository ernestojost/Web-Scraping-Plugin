<?php

defined( 'ABSPATH' ) || exit;

/**
 * Genera el html de un artículo cuando se usa el buscador
 */
function get_html_article($title, $content, $article_id) { 
    return '<div class="web-scraping-article-element" article-id="'.$article_id.'">
        <h4>Title:</h4>
        <input class="web-scraping-article-title" type="text" name="web-title" value="'.$title.'"><br><br>
        <h4>Content:</h4>
        <textarea class="web-scraping-article-content" name="web-content">'.$content.'</textarea><br><br>
        <button class="submit" article-id="'.$article_id.'">Create post</button>
        <button class="format-html" article-id="'.$article_id.'">Format</button>
    </div>';
}
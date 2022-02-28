<?php

defined( 'ABSPATH' ) || exit;

/**
 * Crea un post en el sitio
 */
function scraping_create_post(){  

    if (!isset($_POST['title']) || $_POST['title'] == '') {
        echo json_encode( array(
            "status" => "error",
            "message" => "No se ha proporcionado el título"
        ));
        die;
    } else if (!isset($_POST['content']) || $_POST['content'] == '') {
        echo json_encode( array(
            "status" => "error",
            "message" => "No se ha proporcionado el contenido"
        ));
        die;
    }

    $title = $_POST['title'];
    $content = $_POST['content'];

    global $user_ID;
    $new_post = array(
        'post_title' => $title,
        'post_content' => $content,
        'post_status' => 'publish',
        'post_date' => date('Y-m-d H:i:s'),
        'post_author' => $user_ID,
        'post_type' => 'post',
        'post_category' => array(0)
    );
    $post_id = wp_insert_post($new_post);

    echo json_encode( array(
        "status" => "success",
        "post_id" => $post_id,
    ));
    die;
    
}
add_action('wp_ajax_scraping_create_post', 'scraping_create_post');
add_action('wp_ajax_nopriv_scraping_create_post', 'scraping_create_post');
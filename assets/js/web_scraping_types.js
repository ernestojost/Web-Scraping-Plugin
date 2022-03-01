jQuery(document).ready(function($) {
    $('body').on("click", "#web-scraping-types-scraping #web-scraping-types-scraping-content .web-scraping-type", function(e){
        reset_form_input_types()
        $(this).addClass( "type-selected" );
        $(this).find('input').attr('checked', true);
        click_input_type_article($(this).attr('article-type'));
    });

    $('#web-scraping-import-page #web-scraping-types-scraping-content .web-scraping-type').hover(
        function() {
            if (!$(this).hasClass("type-selected")) {
                $(this).find('img').css('opacity','.8');
                $(this).find('.type-custom-scraping-img').css('border','2px solid #c5c5c5');
            }
        }, function() {
            if (!$(this).hasClass("type-selected")) {
                $(this).find('img').css('opacity','.4');
                $(this).find('.type-custom-scraping-img').css('border','2px solid #e5e5e5');
            }
        }
    );

    // Resetea los estilos de los inputs de tipo de scraping
    function reset_form_input_types() {
        $('#web-scraping-types-scraping #web-scraping-types-scraping-content .web-scraping-type').removeClass( "type-selected" );
        $('#web-scraping-types-scraping #web-scraping-types-scraping-content .web-scraping-type').find('input').attr('checked', false);
        $('#web-scraping-types-scraping #web-scraping-types-scraping-content .web-scraping-type img').css('opacity','.4');
        $('#web-scraping-types-scraping #web-scraping-types-scraping-content .web-scraping-type .type-custom-scraping-img').css('border','2px solid #e5e5e5');
    }

    function click_input_type_article(type){
        // TODO: Va a servir para diferenciar entre diferentes tipos de scraping
        var type_name = convert_type_article_to_text(type);
        var type_description = get_description_type_article(type);
        if(type_name != ""){
            $('#web-scraping-import #web-scraping-type-selected-name').text(type_name);
            $('#web-scraping-import #web-scraping-type-selected-description').text(type_description);
            $('#web-scraping-import').css('display','block');
        }
    }

    function convert_type_article_to_text(type) {
        switch (type) {
            case 'blog-with-articles':
                if(get_language() == 'english') {
                    return 'Blog with articles';
                } else {
                    return 'Blog con artículos';
                }
            default:
                return '';
        }
    }

    function get_description_type_article(type) {
        switch (type) {
            case 'blog-with-articles':
                if(get_language() == 'english') {
                    return 'Scraping a blog with articles where within each article you want to get the title of the article with its content.';
                } else {
                    return 'Hace scraping a un blog con artículos donde dentro de cada artículo se quiere obtener el título del mismo con su contenido';
                }
            default:
                return '';
        }
    }

    function get_language() {
        return $('#wep-scraping-plugin-language').text();
    }
})
jQuery(document).ready(function($) {

    $('#form-web-scraping input[type="text"]').blur(function(){
        if($(this).hasClass("input-error") && $(this).val() != ""){
            $(this).removeClass("input-error");
        }
    });

    $('body').on("click", "#form-web-scraping .submit", function(e){

        if (detect_empty_inputs()) {
            $('#form-web-scraping-panel-notice').text("Hay campos vacíos");
            $('#form-web-scraping-panel-notice').css('display','block');
            return;
        } else {
            $('#form-web-scraping-panel-notice').css('display','none');
        }

        if(get_language() == 'english') {
            $('#web-scraping-data-obtained-notice').text("Scraping...");
        } else {
            $('#web-scraping-data-obtained-notice').text("Scrapeando...");
        }
        
        $('#web-scraping-search-spinner').css('display','block');
        $('#web-scraping-data-obtained').css('display','block');
        $('html, body').animate({
            scrollTop: $("#web-scraping-search-spinner").offset().top
        }, 1000);
        disable_all_buttons_import('#web-scraping-import-page');
    
        $.ajax({
            type : "POST",
            dataType : "json",
            url : my_ajax_object.ajax_url,
            data : {
                action: "get_quantity_articles", 
                url: $('#form-web-scraping-url').val(),
                article: $('.web-scraping-page-body > .form-web-scraping-element > input').val()
            },
            success: function(response) {
                console.log(response);
                if (response.status == "error") {
                    $('#web-scraping-data-obtained-notice').text(response.message);
                    $('#web-scraping-search-spinner').css('display','none');
                    enable_all_buttons_import('#web-scraping-import-page');
                    return;
                }
                if(get_language() == 'english') {
                    $('#web-scraping-data-obtained-notice').text("Obtaining data from " + response.quantity + " articles...");
                } else {
                    $('#web-scraping-data-obtained-notice').text("Obteniendo datos de " + response.quantity + " artículos...");
                }
                
                get_all_articles(response.url_articles, $('.web-scraping-page-body .web-scraping-page-content').val(), $('#form-web-scraping-import').val());
            }
        });   
    
    });

    /**
     * Detecta si hay inputs vacíos y les agrega una clase señalando que están vacíos
     * 
     * @returns bool True si hay inputs vacíos, false si no
     */
    function detect_empty_inputs() {
        var empty_inputs = false;
        $('#form-web-scraping input').each(function(){
            if (!$(this).val()) {
                $(this).addClass('input-error');
                empty_inputs = true;
            }
        });
        return empty_inputs;
    }

    function get_all_articles(url_articles, title_jquery, content_jquery){
        $.ajax({
            type : "POST",
            dataType : "json",
            url : my_ajax_object.ajax_url,
            data : {
                action: "get_all_articles", 
                url_articles: url_articles,
                title_jquery: title_jquery,
                content_jquery: content_jquery,
                reading_format: $('#form-web-scraping-reading-format').val()
            },
            success: function(response) {
                console.log(response);
                $('#web-scraping-search-spinner').css('display','none');
                if(response.articles.length > 0){
                    $('#web-scraping-data-obtained-notice').text(response.articles.length + " articles obtained");
                    response.articles.forEach( function( article ) {
                        article = format_HTML(article);
                        $("#web-scraping-articles").append(article);
                    });
                    enable_all_buttons_import('#web-scraping-import-page');

                    $('body').on("click", ".web-scraping-article-element .submit", function(e){
                        var article_id = $(this).attr('article-id');
                        create_post_by_article(article_id);
                    });

                    $('body').on("click", "#web-scraping-data-obtained-create-all-posts", function(e){
                        disable_all_buttons_import('#web-scraping-import-page');
                        var numArticles = $('#web-scraping-articles .web-scraping-article-element').length;
                        $("#web-scraping-data-obtained-notice").html('<span class="web-scraping-count-published">0</span> of <span class="web-scraping-count-articles">' + numArticles + '</span> articles published');
                        $('#web-scraping-articles .web-scraping-article-element').each(function(index, element){
                            var article_id = $(element).attr('article-id');
                            create_post_by_article(article_id);
                        });
                    });

                    $('body').on("click", "#web-scraping-data-obtained-format-all", function(e){
                        disable_all_buttons_import('#web-scraping-import-page');
                        $('#web-scraping-articles .web-scraping-article-element').each(function(index, element){
                            var article_id = $(element).attr('article-id');
                            format_article(article_id);
                        });
                    });

                    $('body').on("click", ".web-scraping-article-element .format-html", function(e){
                        format_article($(this).attr('article-id'));
                    });

                } else {
                    $('#web-scraping-data-obtained-notice').text("No articles found");
                }
            }
        });   
    }

    /**
     * Deshabilita los botones de cierta parte de la página que se le pase
     * 
     * @param {string} selector Selector de la parte de la página que se desea deshabilitar
     */
    function disable_all_buttons_import(selector) {
        $(selector + ' button').attr('disabled', 'disabled');
        $(selector + ' button').css('opacity','0.5');
        $(selector + ' button').css('cursor','auto');
    }

    /**
     * Habilita los botones de cierta parte de la página que se le pase
     * 
     * @param {string} selector Selector de la parte de la página que se desea habilitar 
     */
    function enable_all_buttons_import(selector) {
        $(selector + ' button').removeAttr("disabled");
        $(selector + ' button').css('opacity','1');
        $(selector + ' button').css('cursor','pointer');
    }

    // Crea un post a partir de un id de articulo
    function create_post_by_article(article_id){
        var title = $('.web-scraping-article-element[article-id=' + article_id + '] .web-scraping-article-title').val();
        var content = $('.web-scraping-article-element[article-id=' + article_id + '] .web-scraping-article-content').val();
        $.ajax({
            type : "POST",
            dataType : "json",
            url : my_ajax_object.ajax_url,
            data : {
                action: "scraping_create_post", 
                title: title,
                content: content
            },
            success: function(response) {
                $('.web-scraping-article-element[article-id=' + article_id + ']').css('display','none');
                $('#web-scraping-data-obtained-notice').text(response.message);
                var count = $('.web-scraping-count-published').text();
                count++;
                $('.web-scraping-count-published').text(count);
                if (count == $('.web-scraping-count-articles').text()) {
                    enable_all_buttons_import('#web-scraping-import-page');
                }
            }
        }); 
    }

    // Formatea el HTML de un articulo
    function format_article(article_id) {
        var html_val = $(".web-scraping-article-element[article-id='" + article_id + "'] .web-scraping-article-content").val();
        $(".web-scraping-article-element[article-id='" + article_id + "'] .web-scraping-article-content").val(format_HTML(html_val));
    }

    // Formatea el HTML para que sea legible
    function format_HTML(html) {
        function parse(html, tab = 0) {
            var tab;
            var html = $.parseHTML(html);
            var formatHtml = new String();   
    
            function setTabs () {
                var tabs = new String();
    
                for (i=0; i < tab; i++){
                  tabs += '\t';
                }
                return tabs;    
            };
            $.each( html, function( i, el ) {
                if (el.nodeName == '#text') {
                    if (($(el).text().trim()).length) {
                        formatHtml += setTabs() + $(el).text().trim() + '\n';
                    }    
                } else {
                    var innerHTML = $(el).html().trim();
                    $(el).html(innerHTML.replace('\n', '').replace(/ +(?= )/g, ''));
                    if ($(el).children().length) {
                        $(el).html('\n' + parse(innerHTML, (tab + 1)) + setTabs());
                        var outerHTML = $(el).prop('outerHTML').trim();
                        formatHtml += setTabs() + outerHTML + '\n'; 
    
                    } else {
                        var outerHTML = $(el).prop('outerHTML').trim();
                        formatHtml += setTabs() + outerHTML + '\n';
                    }      
                }
            });
            return formatHtml;
        };   
        return parse(html.replace(/(\r\n|\n|\r)/gm," ").replace(/ +(?= )/g,''));
    }; 

    function get_language() {
        return $('#wep-scraping-plugin-language').text();
    }
})
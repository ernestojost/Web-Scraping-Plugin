jQuery(document).ready(function($) {

    $('body').on("click", "#form-web-scraping .return", function(e){

        $('#web-scraping-import').css('display','none');
        $('#web-scraping-data-obtained').css('display','none');
        $('#web-scraping-types-scraping').css('display','flex');
    
    });

    $('body').on("click", "#form-web-scraping .submit", function(e){

        $('#web-scraping-data-obtained-notice').text("Scraping...");
        $('#web-scraping-search-spinner').css('display','block');
        toggle_disable_all_buttons_import();
    
        $.ajax({
            type : "POST",
            dataType : "json",
            url : my_ajax_object.ajax_url,
            data : {
                action: "get_quantity_articles", 
                url: $('#form-web-scraping-url').val(),
                article: $('#form-web-scraping-article').val()
            },
            success: function(response) {
                $('#web-scraping-data-obtained-notice').text("Obtaining data from " + response.quantity + " articles...");
                get_all_articles(response.url_articles, $('#form-web-scraping-title').val(), $('#form-web-scraping-import').val());
            }
        });   
    
    });

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
                $('#web-scraping-data-obtained-notice').text(response.articles.length + " articles obtained");
                $('#web-scraping-search-spinner').css('display','none');
                if(response.articles.length > 0){
                    response.articles.forEach( function( article ) {
                        article = format_HTML(article);
                        $("#web-scraping-articles").append(article);
                    });
                    toggle_disable_all_buttons_import();

                    $('body').on("click", ".web-scraping-article-element .submit", function(e){

                        var article_id = $(this).attr('article-id');
                        create_post_by_article(article_id);
                    });

                    $('body').on("click", "#web-scraping-data-obtained-create-all-posts", function(e){
                        toggle_disable_all_buttons_import();
                        var numArticles = $('#web-scraping-articles .web-scraping-article-element').length;
                        $("#web-scraping-data-obtained-notice").html('<span class="web-scraping-count-published">0</span> of <span class="web-scraping-count-articles">' + numArticles + '</span> articles published');
                        $('#web-scraping-articles .web-scraping-article-element').each(function(index, element){
                            var article_id = $(element).attr('article-id');
                            create_post_by_article(article_id);
                        });
                    });

                    $('body').on("click", "#web-scraping-data-obtained-format-all", function(e){
                        toggle_disable_all_buttons_import();
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

    // Alterna deshabilitar o habilitar los botones de la página de importación
    function toggle_disable_all_buttons_import() {
        var attr = $('#web-scraping-import-page button').attr('disabled');
        if(typeof attr !== 'undefined' && attr !== false) {
            $('#web-scraping-import-page button').removeAttr("disabled");
            $('#web-scraping-import-page button').css('opacity','1');
            $('#web-scraping-import-page button').css('cursor','pointer');
        } else {
            $('#web-scraping-import-page button').attr('disabled', 'disabled');
            $('#web-scraping-import-page button').css('opacity','0.5');
            $('#web-scraping-import-page button').css('cursor','auto');
        }
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
                    toggle_disable_all_buttons_import();
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
})
<?php
// Se este arquivo for chamado diretamente, aborte.
if (!defined('WPINC')) {
    die;
}

// Enfileiramento de estilos e scripts públicos
function professionaldirectory_enqueue_scripts() {
    wp_enqueue_style('professionaldirectory-style', plugins_url('/public/css/style.css', PDR_MAIN_FILE));
    
    /*
    // Recupera a chave da API do Google Maps das opções do plugin
    $google_maps_api_key = get_option('myplugin_google_maps_api_key');
   
    // Enfileira o script do Google Maps com a biblioteca Places
    wp_enqueue_script('google-maps', 'https://maps.googleapis.com/maps/api/js?key=' . $google_maps_api_key . '&libraries=places&callback=initAutocomplete', array('professionaldirectory-script'), null, true);
     */

    wp_enqueue_style('pdr-search-form-style', plugins_url('/public/css/search-form.css', PDR_MAIN_FILE));


    // Enfileira o script específico de pesquisa
    //wp_enqueue_script('pdr-search-script', plugins_url('/public/js/search.js', PDR_MAIN_FILE), array('jquery', 'google-maps'), null, true);
    wp_enqueue_script('pdr-search-script', plugins_url('/public/js/search.js', PDR_MAIN_FILE), array('jquery'), null, true);

    // Enfileira o script do seu plugin
    wp_enqueue_script('professionaldirectory-script', plugins_url('/public/js/script.js', PDR_MAIN_FILE), array('jquery'), '', true);


    // Localize o script para disponibilizar a URL do AJAX para o JavaScript
    wp_localize_script('pdr-search-script', 'ajax_object', array('ajax_url' => admin_url('admin-ajax.php')));
}
add_action('wp_enqueue_scripts', 'professionaldirectory_enqueue_scripts');

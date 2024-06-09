<?php
defined('ABSPATH') or die('No script kiddies please!');

// Enfileiramento de estilos e scripts públicos
function rhb_enqueue_scripts() {
    wp_enqueue_style('rhb-style', plugins_url('/public/css/style.css', RHB_MAIN_FILE));
    
    /*
    // Recupera a chave da API do Google Maps das opções do plugin
    $options = get_option('rhb_settings', []);
    $google_maps_api_key = isset($options['rhb_google_maps_api_key']) ? $options['rhb_google_maps_api_key'] : '';

    // Enfileira o script do Google Maps com a biblioteca Places
    wp_enqueue_script('google-maps', 'https://maps.googleapis.com/maps/api/js?key=' . $google_maps_api_key . '&libraries=places&callback=initAutocomplete', array('rhb-script'), null, true);
    */

    wp_enqueue_style('rhb-inquiry-form-style', plugins_url('/public/css/inquiry-form.css', RHB_MAIN_FILE));

    // Enfileira o script específico de Inquiry
    //wp_enqueue_script('rhb-inquiry-script', plugins_url('/public/js/Inquiry.js', RHB_MAIN_FILE), array('jquery', 'google-maps'), null, true);
    wp_enqueue_script('rhb-inquiry-script', plugins_url('/public/js/inquiry.js', RHB_MAIN_FILE), array('jquery'), null, true);

    // Enfileira o script do seu plugin
    wp_enqueue_script('rhb-script', plugins_url('/public/js/script.js', RHB_MAIN_FILE), array('jquery'), '', true);

    // Localize o script para disponibilizar a URL do AJAX para o JavaScript
    wp_localize_script('rhb-inquiry-script', 'ajax_object', array('ajax_url' => admin_url('admin-ajax.php')));
}
add_action('wp_enqueue_scripts', 'rhb_enqueue_scripts');

<?php
    defined('ABSPATH') or die('No script kiddies please!');
    
function enqueue_toastify() {
    $plugin_url = plugin_dir_url(__FILE__);

    // Enfileira o CSS da Toastify
    wp_enqueue_style('toastify-css', $plugin_url . 'lib/toastify/toastify.css');

    // Enfileira o JavaScript da Toastify
    wp_enqueue_script('toastify-js', $plugin_url . 'lib/toastify/toastify.min.js', [], false, true);
    
    // Enfileira o seu próprio script que depende do Toastify
    wp_enqueue_script('my-custom-toastify-script', $plugin_url . 'js/my-custom-toastify-script.js', ['toastify-js'], false, true);
}
add_action('admin_enqueue_scripts', 'enqueue_toastify');

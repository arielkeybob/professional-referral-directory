<?php
// Se este arquivo for chamado diretamente, aborte.
if (!defined('WPINC')) {
    die;
}

function get_form_data() {
    return array(
        'service_type' => sanitize_text_field($_POST['service_type'] ?? ''),
        'name' => sanitize_text_field($_POST['name'] ?? ''),
        'email' => sanitize_email($_POST['email'] ?? ''),
        'address' => sanitize_text_field($_POST['address'] ?? ''),
        // Adicione a lógica para obter o 'service_title'
        'service_title' => get_the_title($service_id) // ou outra lógica apropriada
    );
}

<?php
// Se este arquivo for chamado diretamente, aborte.
if (!defined('WPINC')) {
    die;
}

function get_form_data() {
    // Captura e sanitiza os dados do formulário
    return array(
        'service_type' => sanitize_text_field($_POST['service_type'] ?? ''),
        'service_location' => sanitize_text_field($_POST['service_location'] ?? ''),
        'name' => sanitize_text_field($_POST['name'] ?? ''),
        'email' => sanitize_email($_POST['email'] ?? ''),
        // Remova a linha para 'address' se não estiver mais sendo usada
        // 'address' => sanitize_text_field($_POST['address'] ?? ''),
    );
}


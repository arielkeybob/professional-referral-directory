<?php
// Se este arquivo for chamado diretamente, aborte.
if (!defined('WPINC')) {
    die;
}

function get_form_data() {
    // Captura e sanitiza os dados do formulário
    return array(
        'service_type' => sanitize_text_field($_POST['service_type'] ?? ''),
        'name' => sanitize_text_field($_POST['name'] ?? ''),
        'email' => sanitize_email($_POST['email'] ?? ''),
        'address' => sanitize_text_field($_POST['address'] ?? ''),
        // 'service_title' e 'service_id' são definidos em outro lugar
        // e não precisam ser capturados aqui
    );
}

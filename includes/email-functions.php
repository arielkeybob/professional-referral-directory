<?php
// Se este arquivo for chamado diretamente, aborte.
if ( ! defined( 'WPINC' ) ) {
    die;
}

function send_email_to_service_author($post_id, $user_data) {
    $author_email = get_the_author_meta('user_email', get_post_field('post_author', $post_id));

    // Recupera os e-mails dos administradores selecionados e e-mails adicionais
    $selected_admins = get_option('myplugin_selected_admins', []);
    $manual_emails = explode(',', get_option('myplugin_manual_emails', ''));

    // Prepara os endereços de e-mail para BCC
    $bcc_emails = array_merge($selected_admins, $manual_emails);
    $bcc_emails = array_filter(array_map('trim', $bcc_emails)); // Remove espaços extras e e-mails vazios

    $headers = [];
    foreach ($bcc_emails as $bcc_email) {
        $headers[] = 'Bcc: ' . sanitize_email($bcc_email);
    }

    $name = sanitize_text_field($user_data['name']);
    $email = sanitize_email($user_data['email']);
    $service_type = sanitize_text_field($user_data['service_type']);
    $address = sanitize_text_field($user_data['address']); // A ser implementado

    $subject = "Consulta de Serviço: " . get_the_title($post_id);
    $message = "Nome: $name\nEmail: $email\nTipo de Serviço: $service_type\nEndereço: $address\n\nServiço Encontrado: " . get_the_title($post_id) . "\nID do Post: $post_id";

    wp_mail($author_email, $subject, $message, $headers);
}


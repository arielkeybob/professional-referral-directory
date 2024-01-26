<?php
// Se este arquivo for chamado diretamente, aborte.
if (!defined('WPINC')) {
    die;
}

// Supondo que a função 'get_form_data' esteja definida em 'form-data-functions.php' e incluída no plugin
require_once plugin_dir_path(PDR_MAIN_FILE) . 'public/form-data-functions.php';


function send_email_to_service_author($post_id) {
    $user_data = get_form_data();

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

    // Prepare os dados do e-mail
    $name = sanitize_text_field($user_data['name']);
    $email = sanitize_email($user_data['email']);
    $service_type = sanitize_text_field($user_data['service_type']);
    $service_location = sanitize_text_field($user_data['service_location']); // Adicionado campo service_location

    $subject = __("Service Inquiry:", "professional_directory") . " " . get_the_title($post_id);
    $message = sprintf(
        __("Name: %s\nEmail: %s\nServie Type: %s\nLocation: %s\n\nService Found: %s\nPost ID: %s", "professionaldirectory"),
        $name,
        $email,
        $service_type,
        $service_location,
        get_the_title($post_id),
        $post_id
    );
    
    wp_mail($author_email, $subject, $message, $headers);
}


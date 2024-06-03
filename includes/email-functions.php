<?php
    defined('ABSPATH') or die('No script kiddies please!');

// Inclui a função de captura de dados do formulário
require_once plugin_dir_path(PDR_MAIN_FILE) . 'public/form-data-functions.php';

function send_email_to_service_author($post_id) {
    // Captura os dados do usuário a partir do formulário
    $user_data = get_form_data();

    // Verifica a preferência de e-mail do profissional
    $email_preference = get_post_meta($post_id, '_pdr_email_preference', true);

    // Se o profissional optou por não receber e-mails, retorna sem enviar
    if ($email_preference != '1') {
        return; // Encerra a função se o profissional optou por não receber e-mails
    }

    // Obtém o e-mail do autor do post
    $author_email = get_the_author_meta('user_email', get_post_field('post_author', $post_id));

    // Prepara os dados do e-mail
    $name = sanitize_text_field($user_data['name']);
    $email = sanitize_email($user_data['email']);
    $service_type = sanitize_text_field($user_data['service_type']);
    $service_location = sanitize_text_field($user_data['service_location']);

    // Prepara o assunto e a mensagem
    $subject = __("Service Inquiry:", "referral_hub") . " " . get_the_title($post_id);
    $message = sprintf(
        __("Name: %s\nEmail: %s\nService Type: %s\nLocation: %s\n\nService Found: %s\nPost ID: %s", "referralhub"),
        $name, $email, $service_type, $service_location, get_the_title($post_id), $post_id
    );

    // Envia o e-mail
    wp_mail($author_email, $subject, $message);

    error_log('Enviando email para o autor do post. Post ID: ' . $post_id);
    error_log('Dados do usuário: ' . print_r($user_data, true));
}

function send_admin_notification_emails($post_id) {
    // Captura os dados do usuário a partir do formulário
    $user_data = get_form_data();

    // Recupera e-mails adicionais das configurações do plugin
    $selected_admins = get_option('pdr_selected_admins', []);
    $manual_emails = explode(',', get_option('pdr_manual_emails', ''));

    // Prepara o assunto e a mensagem para os administradores
    $subject = "Admin Notification: Service Inquiry for " . get_the_title($post_id);
    $message = sprintf(
        "A service inquiry was made for:\n\nService Found: %s\nPost ID: %s\n\nInquirer Name: %s\nInquirer Email: %s\nService Type: %s\nLocation: %s",
        get_the_title($post_id), $post_id,
        sanitize_text_field($user_data['name']),
        sanitize_email($user_data['email']),
        sanitize_text_field($user_data['service_type']),
        sanitize_text_field($user_data['service_location'])
    );

    // Envia o e-mail para cada admin
    foreach (array_merge($selected_admins, $manual_emails) as $admin_email) {
        wp_mail(trim($admin_email), $subject, $message);
    }

    error_log('Enviando notificações para administradores. Post ID: ' . $post_id);
}


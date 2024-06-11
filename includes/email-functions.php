<?php
defined('ABSPATH') or die('No script kiddies please!');

// Inclui a função de captura de dados do formulário
require_once plugin_dir_path(RHB_MAIN_FILE) . 'public/form-data-functions.php';

function send_email_to_service_author($post_id) {
    error_log('Tentando enviar e-mail para o autor do serviço com post ID: ' . $post_id);
    
    // Captura os dados do usuário a partir do formulário
    $user_data = get_form_data();
    error_log('Dados do formulário capturados: ' . print_r($user_data, true));

    // Verifica a preferência de e-mail do service provider
    $email_preference = get_post_meta($post_id, '_rhb_email_preference', true);
    error_log('Preferência de e-mail para o post ID ' . $post_id . ': ' . $email_preference);

    // Se o service provider optou por não receber e-mails, retorna sem enviar
    if ($email_preference != '1') {
        error_log('O autor do post optou por não receber e-mails. Post ID: ' . $post_id);
        return; // Encerra a função se o service provider optou por não receber e-mails
    }

    // Obtém o e-mail do autor do post
    $author_email = get_the_author_meta('user_email', get_post_field('post_author', $post_id));
    error_log('E-mail do autor do post: ' . $author_email);

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
    if (wp_mail($author_email, $subject, $message)) {
        error_log('E-mail enviado com sucesso para ' . $author_email);
    } else {
        error_log('Falha ao enviar e-mail para ' . $author_email);
    }
}

function send_admin_notification_emails($post_id) {
    error_log('Tentando enviar notificações para os administradores para o post ID: ' . $post_id);

    // Captura os dados do usuário a partir do formulário
    $user_data = get_form_data();
    error_log('Dados do usuário para notificação administrativa: ' . print_r($user_data, true));

    // Recupera e-mails adicionais das configurações do plugin
    $options = get_option('rhb_settings', []);
    $selected_admins = isset($options['rhb_selected_admins']) ? $options['rhb_selected_admins'] : [];
    $manual_emails = isset($options['rhb_manual_emails']) ? explode(',', $options['rhb_manual_emails']) : [];

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
        if (wp_mail(trim($admin_email), $subject, $message)) {
            error_log('Notificação enviada com sucesso para ' . trim($admin_email));
        } else {
            error_log('Falha ao enviar notificação para ' . trim($admin_email));
        }
    }
}

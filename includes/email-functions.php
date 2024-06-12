<?php
defined('ABSPATH') or die('No script kiddies please!');

// Inclui a função de captura de dados do formulário
require_once plugin_dir_path(RHB_MAIN_FILE) . 'public/form-data-functions.php';

/**
 * Agenda o envio de e-mail para o autor do serviço.
 */
function schedule_email_to_service_author($post_id, $data) {
    wp_schedule_single_event(time(), 'send_email_to_service_author_event', [$post_id, $data]);
}

/**
 * Agenda o envio de notificações para os administradores.
 */
function schedule_admin_notification_emails($post_id, $data) {
    wp_schedule_single_event(time(), 'send_admin_notification_emails_event', [$post_id, $data]);
}

/**
 * Envia um e-mail para o autor do serviço.
 */
function send_email_to_service_author($post_id, $data) {
    error_log('Tentando enviar e-mail para o autor do serviço com post ID: ' . $post_id);
    
    $email_preference = get_post_meta($post_id, '_rhb_email_preference', true);
    if ($email_preference != '1') {
        error_log('O autor do post optou por não receber e-mails. Post ID: ' . $post_id);
        return;
    }

    $author_email = get_the_author_meta('user_email', get_post_field('post_author', $post_id));

    $subject = __("Service Inquiry:", "referral_hub") . " " . get_the_title($post_id);
    $message = sprintf(
        __("Name: %s\nEmail: %s\nService Type: %s\nLocation: %s\n\nService Found: %s\nPost ID: %s", "referralhub"),
        $data['name'], $data['email'], $data['service_type'], $data['service_location'], get_the_title($post_id), $post_id
    );

    if (wp_mail($author_email, $subject, $message)) {
        error_log('E-mail enviado com sucesso para ' . $author_email);
    } else {
        error_log('Falha ao enviar e-mail para ' . $author_email);
    }
}

/**
 * Envia notificações de e-mail para os administradores.
 */
function send_admin_notification_emails($post_id, $data) {
    error_log('Tentando enviar notificações para os administradores para o post ID: ' . $post_id);

    // Recupera e-mails adicionais das configurações do plugin
    $options = get_option('rhb_settings', []);
    $selected_admins = isset($options['rhb_selected_admins']) ? $options['rhb_selected_admins'] : [];
    $manual_emails = isset($options['rhb_manual_emails']) ? explode(',', $options['rhb_manual_emails']) : [];

    // Combinando as listas de e-mails e removendo duplicatas e vazios
    $emails_to_notify = array_filter(array_unique(array_merge($selected_admins, $manual_emails)));

    // Prepara o assunto e a mensagem para os administradores
    $subject = "Admin Notification: Service Inquiry for " . get_the_title($post_id);
    $message = sprintf(
        "A service inquiry was made for:\n\nService Found: %s\nPost ID: %s\n\nInquirer Name: %s\nInquirer Email: %s\nService Type: %s\nLocation: %s",
        get_the_title($post_id), $post_id,
        sanitize_text_field($data['name']),
        sanitize_email($data['email']),
        sanitize_text_field($data['service_type']),
        sanitize_text_field($data['service_location'])
    );

    // Verifica se há e-mails para enviar notificações
    if (!empty($emails_to_notify)) {
        foreach ($emails_to_notify as $admin_email) {
            if (wp_mail(trim($admin_email), $subject, $message)) {
                error_log('Notificação enviada com sucesso para ' . trim($admin_email));
            } else {
                error_log('Falha ao enviar notificação para ' . trim($admin_email));
            }
        }
    } else {
        error_log('Nenhum e-mail adicional especificado para notificação.');
    }
}



function rhb_register_cron_events() {
    add_action('send_email_to_service_author_event', 'send_email_to_service_author', 10, 2);
    add_action('send_admin_notification_emails_event', 'send_admin_notification_emails', 10, 2);
}
add_action('init', 'rhb_register_cron_events');
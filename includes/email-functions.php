<?php
// Verifica se o arquivo foi chamado diretamente
if (!defined('WPINC')) {
    die;
}

// Inclui a função de captura de dados do formulário
require_once plugin_dir_path(PDR_MAIN_FILE) . 'public/form-data-functions.php';

function send_email_to_service_author($post_id) {
    // Captura os dados do usuário a partir do formulário
    $user_data = get_form_data();

    // Verifica a preferência de e-mail do profissional
    $email_preference = get_post_meta($post_id, '_pdr_email_preference', true);

        // Se o profissional optou por receber e-mails, continua com a lógica de envio
    if ($email_preference == '1') {
        // Obtém o e-mail do autor do post
        $author_email = get_the_author_meta('user_email', get_post_field('post_author', $post_id));

        // Prepara os dados do e-mail
        $service_title = get_the_title($post_id);
        $service_url = get_permalink($post_id);
        
        // Assunto do e-mail
        $subject = sprintf('Notificação de Visualização: %s', $service_title);
        
        // Mensagem do e-mail
        $message = sprintf(
            "Olá,\n\nSeu serviço, '%s', foi recentemente visualizado no nosso site.\nVocê pode vê-lo aqui: %s\n\nAtenciosamente,\nEquipe",
            $service_title,
            $service_url
        );
        
        // Cabeçalhos de e-mail (opcional)
        $headers = array('Content-Type: text/plain; charset=UTF-8');
        
        // Envia o e-mail para o autor do serviço
        wp_mail($author_email, $subject, $message, $headers);
    }


    // Independente da preferência do autor, envia e-mails para administradores/e-mails adicionais
    send_admin_notification_emails($post_id);
}

function send_admin_notification_emails($post_id) {
    // Recupera e-mails adicionais das configurações do plugin
    $selected_admins = get_option('myplugin_selected_admins', []);
    $manual_emails = explode(',', get_option('myplugin_manual_emails', ''));

    // Prepara endereços de e-mail para BCC
    $bcc_emails = array_merge($selected_admins, $manual_emails);
    $bcc_emails = array_filter(array_map('trim', $bcc_emails)); // Limpa e remove vazios

    // Prepara os cabeçalhos de e-mail
    $headers = [];
    foreach ($bcc_emails as $email) {
        $headers[] = 'Bcc: ' . sanitize_email($email);
    }

    // Prepara os dados do e-mail (Exemplo simples, ajuste conforme necessário)
    $subject = "Notificação do Serviço: " . get_the_title($post_id);
    $message = "Um serviço foi visualizado: " . get_permalink($post_id);

    // Envia o e-mail
    wp_mail(null, $subject, $message, $headers);
}

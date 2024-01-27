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

    // Obtém o e-mail do autor do post
    $author_email = get_the_author_meta('user_email', get_post_field('post_author', $post_id));

    // Recupera e-mails adicionais das configurações do plugin
    $selected_admins = get_option('myplugin_selected_admins', []);
    $manual_emails = explode(',', get_option('myplugin_manual_emails', ''));

    // Prepara endereços de e-mail para BCC
    $bcc_emails = array_merge($selected_admins, $manual_emails);
    $bcc_emails = array_filter(array_map('trim', $bcc_emails));

    // Prepara os cabeçalhos de e-mail
    $headers = array_map(function($email) { return 'Bcc: ' . sanitize_email($email); }, $bcc_emails);

    // Prepara os dados do e-mail
    $name = sanitize_text_field($user_data['name']);
    $email = sanitize_email($user_data['email']);
    $service_type = sanitize_text_field($user_data['service_type']);
    $service_location = sanitize_text_field($user_data['service_location']); // Campo service_location

    // Prepara o assunto e a mensagem
    $subject = __("Service Inquiry:", "professional_directory") . " " . get_the_title($post_id);
    $message = sprintf(
        __("Name: %s\nEmail: %s\nService Type: %s\nLocation: %s\n\nService Found: %s\nPost ID: %s", "professionaldirectory"),
        $name, $email, $service_type, $service_location, get_the_title($post_id), $post_id
    );
    
    // Envia o e-mail
    wp_mail($author_email, $subject, $message, $headers);


    error_log('Enviando email para o autor do post. Post ID: ' . $post_id);
    error_log('Dados do usuário: ' . print_r($user_data, true));


}

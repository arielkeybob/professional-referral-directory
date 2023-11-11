<?php
// Se este arquivo for chamado diretamente, aborte.
if ( ! defined( 'WPINC' ) ) {
    die;
}

function send_email_to_service_author($post_id, $user_data) {
    $author_email = get_the_author_meta('user_email', get_post_field('post_author', $post_id));

    $name = sanitize_text_field($user_data['name']);
    $email = sanitize_email($user_data['email']);
    $service_type = sanitize_text_field($user_data['service_type']);
    $address = sanitize_text_field($user_data['address']); // A ser implementado

    $subject = "Consulta de Serviço: " . get_the_title($post_id);
    $message = "Nome: $name\nEmail: $email\nTipo de Serviço: $service_type\nEndereço: $address\n\nServiço Encontrado: " . get_the_title($post_id) . "\nID do Post: $post_id";

    wp_mail($author_email, $subject, $message);
}

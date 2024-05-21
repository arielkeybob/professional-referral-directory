<?php
// Se este arquivo for chamado diretamente, aborte.
defined('ABSPATH') or die('No script kiddies please!');

/**
 * Handler para salvar detalhes de contato via AJAX.
 *
 * Esta função é chamada através de uma requisição AJAX para salvar os detalhes do contato.
 * Verifica permissões, valida nonce, sanitiza dados e atualiza o banco de dados.
 */
function pdr_save_contact_details_ajax_handler() {
    // Certifique-se de que o nonce foi enviado e é válido
    check_ajax_referer('update_contact_' . $_POST['contact_id'], 'nonce');

    // Verifica se o usuário tem permissão para executar esta ação
    if (!current_user_can('view_pdr_contacts')) {
        wp_send_json_error(['message' => 'Permissão insuficiente.']);
        exit;
    }

    global $wpdb;
    $contact_id = isset($_POST['contact_id']) ? intval($_POST['contact_id']) : 0;
    $author_id = get_current_user_id(); // Obtém o ID do autor atual
    $new_status = isset($_POST['contact_status']) ? sanitize_text_field($_POST['contact_status']) : '';
    $custom_name = isset($_POST['custom_name']) ? sanitize_text_field($_POST['custom_name']) : '';

    // Atualiza o status e o nome customizado do contato se o autor bate com o atual
    $updated = $wpdb->update(
        "{$wpdb->prefix}pdr_author_contact_relations",
        ['status' => $new_status, 'custom_name' => $custom_name],
        ['contact_id' => $contact_id, 'author_id' => $author_id] // Condição adicional para o author_id
    );

    // Verifica se a atualização foi bem-sucedida
    if (false === $updated) {
        error_log('Erro ao atualizar o contato: ' . $wpdb->last_error);
        wp_send_json_error(['message' => 'Erro ao atualizar o contato.']);
        exit;
    }

    // Inicializa a variável para rastrear erros nas atualizações das pesquisas
    $errors = false;
    
    // Atualiza o status das pesquisas se o autor bate com o atual
    foreach ($_POST['searches'] as $search_id => $search_status) {
        $search_id_sanitized = intval($search_id);
        $status_sanitized = sanitize_text_field($search_status);

        // Atualiza o status da pesquisa no banco de dados
        $search_updated = $wpdb->update(
            "{$wpdb->prefix}pdr_search_data",
            ['search_status' => $status_sanitized],
            ['id' => $search_id_sanitized, 'author_id' => $author_id] // Condição adicional para o author_id
        );

        // Verifica se a atualização da pesquisa foi bem-sucedida
        if (false === $search_updated) {
            error_log("Erro ao atualizar o status da pesquisa ID $search_id: " . $wpdb->last_error);
            $errors = true;
        }
        error_log("A pesquisa $search_id foi salva como $status_sanitized ");
    }

    // Verifica se houve erros nas atualizações das pesquisas
    if ($errors) {
        wp_send_json_error(['message' => 'Erro ao atualizar o status de algumas ou todas as pesquisas.']);
    } else {
        wp_send_json_success(['message' => 'Informações atualizadas com sucesso.']);
    }

    exit;
}

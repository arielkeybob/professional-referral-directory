<?php
defined('ABSPATH') or die('No script kiddies please!');

/**
 * Handler para salvar detalhes de contato via AJAX.
 *
 * Esta função é chamada através de uma requisição AJAX para salvar os detalhes do contato
 * e atualizar o status das pesquisas associadas, incluindo o cálculo e ajuste das comissões.
 */
function pdr_save_contact_details_ajax_handler() {
    check_ajax_referer('update_contact_' . $_POST['contact_id'], 'nonce');

    if (!current_user_can('view_pdr_contacts')) {
        wp_send_json_error(['message' => 'Permissão insuficiente.']);
        exit;
    }

    global $wpdb;
    $contact_id = intval($_POST['contact_id']);
    $author_id = get_current_user_id();
    $custom_name = sanitize_text_field($_POST['custom_name']);
    $new_status = sanitize_text_field($_POST['contact_status']); // Garanta que este campo esteja sendo enviado corretamente
    $errors = false;

    $updated = $wpdb->update(
        "{$wpdb->prefix}pdr_author_contact_relations",
        [
            'custom_name' => $custom_name,
            'status' => $new_status // Assegure que está atualizando o status
        ],
        ['contact_id' => $contact_id, 'author_id' => $author_id]
    );

    if (!$updated && $wpdb->last_error) {
        error_log('Erro ao atualizar o contato: ' . $wpdb->last_error);
        wp_send_json_error(['message' => 'Erro ao atualizar o contato.']);
        exit;
    }

    foreach ($_POST['searches'] as $search_id => $search_status) {
        $search_id_sanitized = intval($search_id);
        $status_sanitized = sanitize_text_field($search_status);

        require_once('commission-calculator.php');
        $commissions = calculate_commissions($author_id, $status_sanitized);

        $search_updated = $wpdb->update("{$wpdb->prefix}pdr_search_data", [
            'search_status' => $status_sanitized,
            'commission_value_view' => $commissions['view'],
            'commission_value_approval' => ($status_sanitized === 'approved') ? $commissions['approval'] : 0.00
        ], [
            'id' => $search_id_sanitized,
            'author_id' => $author_id
        ]);

        if (!$search_updated && $wpdb->last_error) {
            error_log("Erro ao atualizar o status da pesquisa ID $search_id: " . $wpdb->last_error);
            $errors = true;
        }
    }

    if ($errors) {
        wp_send_json_error(['message' => 'Erro ao atualizar o status de algumas ou todas as pesquisas.']);
    } else {
        wp_send_json_success(['message' => 'Informações atualizadas com sucesso.']);
    }

    exit;
}



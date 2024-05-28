<?php
defined('ABSPATH') or die('No script kiddies please!');

require_once('commission-calculator.php');

/**
 * Adiciona ou atualiza um contato na tabela 'pdr_contacts'.
 */
function adicionar_ou_atualizar_contato($dados) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'pdr_contacts';
    if (!isset($dados['email']) || empty($dados['email'])) {
        return false;
    }
    $email = $dados['email'];
    $name = $dados['name'] ?? '';

    $contact = $wpdb->get_row($wpdb->prepare("SELECT contact_id FROM $table_name WHERE email = %s", $email));
    if ($contact) {
        $wpdb->update($table_name, ['default_name' => $name], ['email' => $email]);
        return $contact->contact_id;
    } else {
        $wpdb->insert($table_name, ['email' => $email, 'default_name' => $name]);
        return $wpdb->insert_id;
    }
}

/**
 * Cria ou atualiza a relação entre contato e autor na tabela 'wp_pdr_author_contact_relations'.
 */
function createOrUpdateContactAuthorRelation($contactId, $authorId, $status = 'active', $customName = null) {
    global $wpdb;
    $relationTable = $wpdb->prefix . 'pdr_author_contact_relations';

    $existingRelation = $wpdb->get_row($wpdb->prepare(
        "SELECT author_contact_id FROM $relationTable WHERE contact_id = %d AND author_id = %d",
        $contactId, $authorId
    ));
    if (!$existingRelation) {
        $data = ['contact_id' => $contactId, 'author_id' => $authorId, 'status' => $status];
        if ($customName !== null) {
            $data['custom_name'] = $customName;
        }
        $wpdb->insert($relationTable, $data);
    }
}

/**
 * Armazena os dados da pesquisa associando-os automaticamente a um contato.
 */
function store_search_data($data) {
    global $wpdb;
    $searchDataTable = $wpdb->prefix . 'pdr_search_data';
    
    // Verifica se todos os campos necessários estão presentes
    if (!isset($data['service_type'], $data['service_location'], $data['contact_id'], $data['author_id'])) {
        error_log('Dados necessários ausentes para inserção em wp_pdr_search_data.');
        return false;
    }

    // Adiciona a data atual se não for fornecida
    if (!isset($data['search_date'])) {
        $data['search_date'] = current_time('mysql');
    }

    // Calcula as comissões com base nas configurações do autor, se houver sobrescrição
    $commissions = calculate_commissions($data['author_id']);

    // Prepara os dados para inserção, incluindo os valores de comissão
    $insertData = [
        'service_type' => $data['service_type'],
        'service_location' => $data['service_location'],
        'search_date' => $data['search_date'],
        'service_id' => $data['service_id'] ?? 0,
        'author_id' => $data['author_id'],
        'contact_id' => $data['contact_id'],
        'search_status' => $data['search_status'] ?? 'pending',
        'commission_value_view' => $commissions['view'],
        'commission_value_approval' => $commissions['approval']
    ];

    // Insere os dados na tabela
    if (!$wpdb->insert($searchDataTable, $insertData)) {
        error_log('Erro ao inserir dados em wp_pdr_search_data: ' . $wpdb->last_error);
        return false;
    }

    // Loga o ID do registro inserido para referência futura
    error_log("Dados de pesquisa inseridos com sucesso, ID: " . $wpdb->insert_id);
    return true;
}

<?php
defined('ABSPATH') or die('No script kiddies please!');

/**
 * Adiciona ou atualiza um contato na tabela 'pdr_contacts'.
 */
function adicionar_ou_atualizar_contato($dados) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'pdr_contacts';
    if (!isset($dados['email']) || empty($dados['email'])) {
        // Tratar erro ou retornar valor que indique falha
        return false;
    }
    $email = $dados['email'];
    $name = $dados['name'] ?? ''; // Assume que 'name' pode não estar definido

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
 * Cria ou atualiza a relação entre contato e autor.
 */
function createOrUpdateContactAuthorRelation($contactId, $authorId, $postId, $status = 'active', $customName = null) {
    global $wpdb;
    $relationTable = $wpdb->prefix . 'pdr_contact_author_relation';

    // Verificar se já existe uma relação
    $existingRelation = $wpdb->get_row($wpdb->prepare(
        "SELECT relation_id FROM $relationTable WHERE contact_id = %d AND author_id = %d AND post_id = %d",
        $contactId, $authorId, $postId
    ));

    // Se existir, atualiza. Se não, cria uma nova.
    if (null !== $existingRelation) {
        $wpdb->update($relationTable, [
            'status' => $status           
        ], [
            'relation_id' => $existingRelation->relation_id
        ]);
    } else {
        $wpdb->insert($relationTable, [
            'contact_id' => $contactId,
            'author_id' => $authorId,
            'post_id' => $postId,
            'status' => $status
        ]);
    }
}


/**
 * Armazena os dados da pesquisa associando-os automaticamente a um contato.
 */
function store_search_data($data) {
    global $wpdb;
    $searchDataTable = $wpdb->prefix . 'pdr_search_data';
    
    // Certifique-se de que tanto 'email' quanto 'name' estão presentes
    if (empty($data['email']) || !isset($data['name'])) {
        // Trate a falta de dados essenciais conforme necessário
        error_log('Email ou nome não fornecido para store_search_data.');
        return false; // Ou outra maneira de indicar falha
    }

    // Defina search_date se não estiver no array $data
    if (!array_key_exists('search_date', $data)) {
        $data['search_date'] = current_time('mysql');
    }
    
    // Obter ou criar contact_id baseado no e-mail fornecido
    $contactId = adicionar_ou_atualizar_contato(['email' => $data['email'], 'name' => $data['name']]);
    if (!$contactId) {
        // Falha ao obter ou criar contact_id
        error_log('Falha ao obter ou criar contact_id em store_search_data.');
        return false;
    }

    // Preparar dados para inserção, associando a pesquisa ao contact_id
    $data['contact_id'] = $contactId;
    // Se você decidir remover o 'email' após obter o 'contact_id', faça isso aqui
    // Mas, como você mencionou a necessidade de manter o 'name', ele não será removido
    unset($data['email']); // Ajuste conforme a estrutura da sua tabela wp_pdr_search_data

    // Verifique se todos os campos necessários estão presentes antes da inserção
    if (!isset($data['service_type']) || !isset($data['service_location']) || !isset($data['search_date']) || !isset($data['contact_id'])) {
        error_log('Dados necessários ausentes para inserção em wp_pdr_search_data.');
        return false;
    }

    // Inserir dados da pesquisa na tabela
    $result = $wpdb->insert($searchDataTable, $data);

    if ($result === false) {
        // Tratar erro de inserção
        error_log('Erro ao inserir dados em wp_pdr_search_data: ' . $wpdb->last_error);
        return false;
    }

    return true; // Sucesso
}


// Aqui, você pode adicionar quaisquer outras funções relacionadas ao armazenamento de dados que seu plugin necessite.

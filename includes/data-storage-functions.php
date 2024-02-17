<?php
defined('ABSPATH') or die('No script kiddies please!');

/**
 * Adiciona ou atualiza um contato na tabela 'pdr_contacts'.
 */
function adicionar_ou_atualizar_contato($dados) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'pdr_contacts';
    $email = $dados['email'];
    $name = $dados['name'] ?? ''; // Assume que 'name' pode não estar definido

    // Verificar se o contato já existe
    $contact = $wpdb->get_row($wpdb->prepare("SELECT contact_id FROM $table_name WHERE email = %s", $email));

    if ($contact) {
        // Atualiza o contato existente
        $wpdb->update($table_name, ['default_name' => $name], ['email' => $email]);
    } else {
        // Insere um novo contato
        $wpdb->insert($table_name, ['email' => $email, 'default_name' => $name]);
        return $wpdb->insert_id;
    }
    return $contact->contact_id;
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

    if ($existingRelation) {
        // Atualiza a relação existente se necessário
        $wpdb->update($relationTable, ['status' => $status, 'custom_name' => $customName], ['relation_id' => $existingRelation->relation_id]);
    } else {
        // Cria uma nova relação
        $wpdb->insert($relationTable, ['contact_id' => $contactId, 'author_id' => $authorId, 'post_id' => $postId, 'status' => $status, 'custom_name' => $customName]);
    }
}

/**
 * Armazena os dados da pesquisa associando-os automaticamente a um contato.
 */
function store_search_data($data) {
    global $wpdb;
    $searchDataTable = $wpdb->prefix . 'pdr_search_data';
    
    // Defina search_date se não estiver no array $data
    if (!array_key_exists('search_date', $data)) {
        $data['search_date'] = current_time('mysql');
    }
    
    // Obter ou criar contact_id baseado no e-mail fornecido
    $contactId = adicionar_ou_atualizar_contato(['email' => $data['email'], 'name' => $data['name']]);

    // Preparar dados para inserção, associando a pesquisa ao contact_id
    $data['contact_id'] = $contactId;
    unset($data['email']); // Removendo chaves não necessárias para a tabela de pesquisa

    // Inserir dados da pesquisa na tabela
    $wpdb->insert($searchDataTable, $data);
}

// Aqui, você pode adicionar quaisquer outras funções relacionadas ao armazenamento de dados que seu plugin necessite.

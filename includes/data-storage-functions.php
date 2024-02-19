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
/**
 * Cria ou atualiza a relação entre contato e autor na tabela 'wp_pdr_author_contact_relations'.
 */
function createOrUpdateContactAuthorRelation($contactId, $authorId, $status = 'active', $customName = null) {
    global $wpdb;
    $relationTable = $wpdb->prefix . 'pdr_author_contact_relations';

    // Verificar se já existe uma relação para o par contato-autor
    $existingRelation = $wpdb->get_row($wpdb->prepare(
        "SELECT author_contact_id FROM $relationTable WHERE contact_id = %d AND author_id = %d",
        $contactId, $authorId
    ));

    // Se não existir, cria uma nova relação
    if (!$existingRelation) {
        $data = [
            'contact_id' => $contactId,
            'author_id' => $authorId,
            'status' => $status,
        ];
        
        // Se um nome personalizado foi fornecido, inclua-o nos dados
        if ($customName !== null) {
            $data['custom_name'] = $customName;
        }

        $wpdb->insert($relationTable, $data);
    }
    // Se a relação já existir, não fazemos nada
}




/**
 * Armazena os dados da pesquisa associando-os automaticamente a um contato.
 */
function store_search_data($data) {
    global $wpdb;
    $searchDataTable = $wpdb->prefix . 'pdr_search_data';
    
    // Assegura que os campos necessários estão presentes
    if (!isset($data['service_type'], $data['service_location'], $data['contact_id'], $data['author_id'])) {
        error_log('Dados necessários ausentes para inserção em wp_pdr_search_data.');
        return false;
    }

    // Adiciona a data atual se não for fornecida
    if (!isset($data['search_date'])) {
        $data['search_date'] = current_time('mysql');
    }

    // Inserir dados da pesquisa na tabela, incluindo o status da pesquisa
    $insertData = [
        'service_type' => $data['service_type'],
        'service_location' => $data['service_location'],
        'search_date' => $data['search_date'],
        'service_id' => $data['service_id'] ?? 0, // Adiciona 0 se service_id não estiver definido
        'author_id' => $data['author_id'],
        'contact_id' => $data['contact_id'],
        'search_status' => $data['search_status'] ?? 'pending', // Usa 'pending' como padrão se não definido
    ];

    if (!$wpdb->insert($searchDataTable, $insertData)) {
        error_log('Erro ao inserir dados em wp_pdr_search_data: ' . $wpdb->last_error);
        return false;
    }

    return true; // Sucesso
}


// Aqui, você pode adicionar quaisquer outras funções relacionadas ao armazenamento de dados que seu plugin necessite.

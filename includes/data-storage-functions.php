<?php
// Prevenção contra acesso direto ao arquivo
if (!defined('WPINC')) {
    die;
}
/*
function store_search_data($data) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'pdr_search_data';

    // Inserir dados na tabela, incluindo o service_id
    $result = $wpdb->insert(
        $table_name,
        array(
            'service_type' => $data['service_type'],
            'name' => $data['name'],
            'email' => $data['email'],
            'address' => $data['address'],
            'service_id' => $data['service_id'], // ID do serviço
            'search_date' => current_time('mysql'),
        ),
        array('%s', '%s', '%s', '%s', '%d', '%s') // Tipos de dados correspondentes
    );

    if ($result === false) {
        // Tratar erro de inserção aqui
    }
}
*/

function store_search_data($data) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'pdr_search_data';

    // Adicionando log para verificar se a função foi chamada
    error_log('store_search_data chamada. Dados recebidos: ' . json_encode($data));

    // Inserir dados na tabela
    $result = $wpdb->insert(
        $table_name,
        $data,
        array('%s', '%s', '%s', '%s', '%d', '%d', '%s') // Atualizando os tipos de dados
    );

    // Verificando se ocorreu algum erro durante a inserção
    if ($result === false) {
        error_log('Erro ao inserir dados no banco de dados: ' . $wpdb->last_error);
    } else {
        error_log('Dados inseridos com sucesso no banco de dados.');
    }
}



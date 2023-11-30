<?php
// Prevenção contra acesso direto ao arquivo
if (!defined('WPINC')) {
    die;
}


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



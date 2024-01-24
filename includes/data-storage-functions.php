<?php
// Prevenção contra acesso direto ao arquivo
if (!defined('WPINC')) {
    die;
}


function store_search_data($data) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'pdr_search_data';

    // Certifique-se de que 'service_location' esteja no array $data
    // Se não estiver, adicione-o (substitua 'valor_padrao' pelo valor que você deseja usar se estiver ausente)
    if (!array_key_exists('service_location', $data)) {
        $data['service_location'] = 'valor_padrao';
    }

    // Inserir dados na tabela
    $result = $wpdb->insert(
        $table_name,
        $data,
        array('%s', '%s', '%s', '%s', '%d', '%d', '%s') // Atualize os formatos conforme necessário
    );

    // Verificando se ocorreu algum erro durante a inserção
    if ($result === false) {
        error_log('Erro ao inserir dados no banco de dados: ' . $wpdb->last_error);
    } else {
        error_log('Dados inseridos com sucesso no banco de dados.');
    }
}



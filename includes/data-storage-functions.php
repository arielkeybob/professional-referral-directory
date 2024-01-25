<?php
// Prevenção contra acesso direto ao arquivo
if (!defined('WPINC')) {
    die;
}

function store_search_data($data) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'pdr_search_data';

    // Log iniciando a função store_search_data
    error_log('Iniciando store_search_data');

    // Certifique-se de que 'service_location' esteja no array $data
    if (!array_key_exists('service_location', $data)) {
        $data['service_location'] = 'valor_padrao';
    }

    // Log dos dados que serão inseridos
    error_log('Dados a serem inseridos: ' . print_r($data, true));

    // Inserir dados na tabela
    $result = $wpdb->insert(
        $table_name,
        $data,
        array('%s', '%s', '%s', '%s') // Formatando corretamente para strings
    );

    // Verificando se ocorreu algum erro durante a inserção
    if ($result === false) {
        error_log('Erro ao inserir dados no banco de dados: ' . $wpdb->last_error);
    } else {
        error_log('Dados inseridos com sucesso no banco de dados.');
    }

    // Log finalizando a função
    error_log('Finalizando store_search_data');
}

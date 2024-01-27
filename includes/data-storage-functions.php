<?php
// Prevenção contra acesso direto ao arquivo
if (!defined('WPINC')) {
    die;
}

function store_search_data($data) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'pdr_search_data';

    // Log iniciando a função store_search_data
    //error_log('Iniciando store_search_data');
    //error_log('Dados recebidos antes de qualquer modificação: ' . print_r($data, true));

    // Certifique-se de que 'service_location' esteja no array $data
    if (!array_key_exists('service_location', $data)) {
        $data['service_location'] = 'valor_padrao';
    }

    // Adicionando a data e hora atuais para 'search_date' se não estiver no array $data
    if (!array_key_exists('search_date', $data)) {
       $data['search_date'] = current_time('mysql');
    }
    //error_log('Dados após configurações de service_location e search_date: ' . print_r($data, true));

    // Certifique-se de que 'service_id' e 'author_id' estão no array $data
    // Esses campos devem ser fornecidos pelo método que chama esta função
    if (!array_key_exists('service_id', $data) || !array_key_exists('author_id', $data)) {
        // Log de erro ou manipulação se 'service_id' ou 'author_id' não estiverem presentes
        //error_log('service_id ou author_id não fornecidos para store_search_data');
        return;
    }

    // Log dos dados que serão inseridos
    //error_log('Dados a serem inseridos: ' . print_r($data, true));
    // Log da data que será inserida
    //error_log('Data a ser inserida: ' . $data['search_date']);

    // Inserir dados na tabela
    $result = $wpdb->insert(
        $table_name,
        $data,
        array('%s', '%s', '%s', '%s', '%d', '%d', '%s') // Formatos atualizados para incluir os novos campos
    );

    //error_log('Resultado da inserção: ' . ($result ? 'Sucesso' : 'Falha'));


    // Verificando se ocorreu algum erro durante a inserção
    /*
     if ($result === false) {
        error_log('Erro ao inserir dados no banco de dados: ' . $wpdb->last_error);
    } else {
        error_log('Dados inseridos com sucesso no banco de dados.');
    }

    // Log finalizando a função
    error_log('Finalizando store_search_data');
    */
}

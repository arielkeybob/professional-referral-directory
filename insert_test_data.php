<?php
defined('ABSPATH') or die('No script kiddies please!');
/*
function insert_fixed_search_data() {
    global $wpdb;
    $searchDataTable = $wpdb->prefix . 'pdr_search_data';

    // Recupera a comissão de visualização das configurações do plugin
    $commission_view = get_option('pdr_general_commission_view', '0.05'); // Usa '0.05' como padrão se não houver valor configurado
    $commission_approval = get_option('pdr_general_commission_approval', '0.10'); // Usa '0.10' como padrão se não houver valor configurado

    // Trata a entrada para garantir que o formato decimal está correto, convertendo vírgulas para pontos
    $normalized_commission_view = str_replace(',', '.', $commission_view);
    $normalized_commission_approval = str_replace(',', '.', $commission_approval);

    // Converte o valor normalizado para float e formata para garantir que sempre haverá dois dígitos após o ponto
    $commission_view_float = number_format(floatval($normalized_commission_view), 2, '.', '');
    $commission_approval_float = number_format(floatval($normalized_commission_approval), 2, '.', '');

    // Valores fixos para teste com comissão dinâmica
    $fixedData = [
        'service_type' => 'design',
        'service_location' => 'rj',
        'search_date' => current_time('mysql'),
        'service_id' => 1,
        'author_id' => 1,
        'contact_id' => 1,
        'search_status' => 'pending',
        'commission_value_view' => $commission_view_float, // Usa o valor da configuração convertido para float para visualização
        'commission_value_approval' => $commission_approval_float, // Usa o valor da configuração convertido para float para aprovação
        'is_paid' => 0
    ];

    // Executa a inserção
    if (!$wpdb->insert($searchDataTable, $fixedData)) {
        error_log('Erro ao inserir dados fixos em wp_pdr_search_data: ' . $wpdb->last_error);
    } else {
        error_log('Dados fixos inseridos com sucesso, ID: ' . $wpdb->insert_id);
    }
}

// Chama a função para inserir os dados
insert_fixed_search_data();
*/?>

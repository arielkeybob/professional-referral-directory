<?php
// Verifique se este arquivo não está sendo acessado diretamente.
if (!defined('WPINC')) {
    die;
}

/**
 * Busca o total de pesquisas por um serviço específico.
 *
 * @param int $service_id ID do serviço.
 * @return int Total de pesquisas para o serviço.
 */
function pdr_get_total_searches_by_service($service_id) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'pdr_search_data';
    $query = $wpdb->prepare("SELECT COUNT(*) FROM $table_name WHERE service_id = %d", $service_id);
    return (int) $wpdb->get_var($query);
}

/**
 * Recupera as pesquisas mais recentes.
 *
 * @param int $limit Número de pesquisas a serem retornadas.
 * @return array Lista das pesquisas mais recentes.
 */
function pdr_get_recent_searches($limit = 5) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'pdr_search_data';
    $query = $wpdb->prepare("SELECT * FROM $table_name ORDER BY search_date DESC LIMIT %d", $limit);
    return $wpdb->get_results($query, ARRAY_A);
}

/**
 * Calcula a distribuição das pesquisas por tipo de serviço.
 *
 * @return array Distribuição das pesquisas.
 */
function pdr_get_searches_distribution_by_service_type() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'pdr_search_data';
    $query = "SELECT service_type, COUNT(*) as total FROM $table_name GROUP BY service_type";
    return $wpdb->get_results($query, ARRAY_A);
}

// Outras funções podem ser adicionadas aqui conforme necessário.
function pdr_get_services_by_current_user() {
    global $wpdb;
    $current_user_id = get_current_user_id();
    $post_type = 'professional_service'; // Substitua pelo seu tipo de post específico

    $query = $wpdb->prepare("SELECT ID, post_title FROM $wpdb->posts WHERE post_type = %s AND post_author = %d", $post_type, $current_user_id);
    return $wpdb->get_results($query, ARRAY_A);
}



function pdr_get_recent_searches_for_user($limit = 10) {
    global $wpdb;
    $current_user_id = get_current_user_id();
    $table_name = $wpdb->prefix . 'pdr_search_data';
    $post_table = $wpdb->posts;

    $query = $wpdb->prepare(
        "SELECT sd.service_type, sd.search_date, p.post_title, sd.name, sd.email, sd.service_location
         FROM $table_name as sd
         INNER JOIN $post_table as p ON sd.service_id = p.ID
         WHERE p.post_author = %d
         ORDER BY sd.search_date DESC 
         LIMIT %d", 
         $current_user_id, $limit
    );

    return $wpdb->get_results($query, ARRAY_A);
}



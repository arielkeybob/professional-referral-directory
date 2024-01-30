<?php
// Hook para a resposta AJAX
add_action('wp_ajax_fetch_admin_dashboard_data', 'fetch_admin_dashboard_data');

// Função para lidar com a requisição AJAX
function fetch_admin_dashboard_data() {
    global $wpdb; // Objeto global para operações no banco de dados

    // Obter os parâmetros do POST
    $period = isset($_POST['period']) ? sanitize_text_field($_POST['period']) : '';
    $includeNoSearch = isset($_POST['include_no_search']) ? filter_var($_POST['include_no_search'], FILTER_VALIDATE_BOOLEAN) : false;
    $startDate = isset($_POST['start_date']) ? sanitize_text_field($_POST['start_date']) : '';
    $endDate = isset($_POST['end_date']) ? sanitize_text_field($_POST['end_date']) : '';

    // Nome da tabela
    $table_name = $wpdb->prefix . 'pdr_search_data';

    // Construindo a consulta SQL
    $sql = "
        SELECT 
            p.ID as post_id,
            p.post_title as service_name,
            COUNT(*) as search_count,
            MAX(s.search_date) as last_search,
            u.display_name as author_name
        FROM $table_name s
        INNER JOIN {$wpdb->posts} p ON s.service_id = p.ID
        INNER JOIN {$wpdb->users} u ON s.author_id = u.ID
        WHERE 1=1 ";

    // Adicionando condições com base no período
    switch ($period) {
        case 'today':
            $sql .= " AND DATE(s.search_date) = CURDATE()";
            break;
        case 'last_week':
            $sql .= " AND DATE(s.search_date) >= CURDATE() - INTERVAL 7 DAY";
            break;
        case 'last_month':
            $sql .= " AND DATE(s.search_date) >= CURDATE() - INTERVAL 1 MONTH";
            break;
        case 'this_year':
            $sql .= " AND YEAR(s.search_date) = YEAR(CURDATE())";
            break;
        case 'custom':
            $sql .= $wpdb->prepare(" AND DATE(s.search_date) BETWEEN %s AND %s", $startDate, $endDate);
            break;
    }

    // Considerar 'includeNoSearch'
    if (!$includeNoSearch) {
        $sql .= " AND p.post_status = 'publish'";
    }

    // Agrupar por ID do serviço
    $sql .= " GROUP BY s.service_id";

    // Debug - Log the SQL query
    //error_log('Executing SQL Query: ' . $sql);

    // Executando a consulta
    $results = $wpdb->get_results($sql, ARRAY_A);

    // Debug - Verificar resultados
    
    
    if (empty($results)) {
        error_log('No results found.');
        wp_send_json_error('No data found.');
    } else {
        error_log('Results found: ' . print_r($results, true));
        wp_send_json_success($results);
    }
    
    

    // Sempre finalize funções AJAX com wp_die()
    wp_die();
}
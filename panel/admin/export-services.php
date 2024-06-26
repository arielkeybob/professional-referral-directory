<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Verifica se a solicitação para exportar dados foi feita
if (isset($_POST['rhb_export_data']) && current_user_can('manage_options')) {
    global $wpdb;

    $filename = 'rhb-data-export-' . date('Y-m-d') . '.csv';
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename=' . $filename);
    header('Pragma: no-cache');
    header('Expires: 0');

    $output = fopen('php://output', 'w');

    // Função para exportar uma tabela
    function export_table($table_name, $columns, $output) {
        global $wpdb;

        // Escreve o nome da tabela
        fputcsv($output, [$table_name]);

        // Escreve os nomes das colunas
        fputcsv($output, $columns);

        // Busca os dados da tabela
        $results = $wpdb->get_results("SELECT * FROM $table_name", ARRAY_A);

        // Escreve os dados da tabela
        if ($results) {
            foreach ($results as $row) {
                fputcsv($output, $row);
            }
        }

        // Adiciona uma linha em branco para separar as tabelas
        fputcsv($output, []);
    }

    // Exporta a tabela rhb_contacts
    export_table($wpdb->prefix . 'rhb_contacts', ['contact_id', 'email', 'default_name'], $output);

    // Exporta a tabela rhb_inquiry_data
    export_table($wpdb->prefix . 'rhb_inquiry_data', ['id', 'service_type', 'service_location', 'inquiry_date', 'service_id', 'author_id', 'contact_id', 'inquiry_status'], $output);

    // Exporta a tabela rhb_author_contact_relations
    export_table($wpdb->prefix . 'rhb_author_contact_relations', ['author_contact_id', 'contact_id', 'author_id', 'status', 'custom_name'], $output);

    fclose($output);
    exit;
}
?>

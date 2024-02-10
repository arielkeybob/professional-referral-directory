<?php
function pdrCreateSearchDataTable() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'pdr_search_data';
    $charset_collate = $wpdb->get_charset_collate();

    // SQL para criar ou modificar a tabela
    $sql = "CREATE TABLE IF NOT EXISTS $table_name (
        id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        service_type VARCHAR(255) NOT NULL,
        name VARCHAR(255),
        email VARCHAR(255),
        service_location VARCHAR(255),
        search_date DATETIME NOT NULL,
        service_id BIGINT UNSIGNED,
        author_id BIGINT UNSIGNED
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-panel/includes/upgrade.php');
  
    dbDelta($sql);

    // Criação de índices
    $indices = [
        'author_id' => 'idx_author_id',
        'service_id' => 'idx_service_id',
        'service_type' => 'idx_service_type',
        'search_date' => 'idx_search_date'
    ];

    foreach ($indices as $column => $index_name) {
        if (!$wpdb->query("SHOW INDEX FROM $table_name WHERE Key_name = '$index_name'")) {
            $wpdb->query("CREATE INDEX $index_name ON $table_name ($column)");
        }
    }
}


function pdrCheckVersion() {
    if ( get_option( 'pdr_version' ) !== PDR_VERSION ) {
        // A versão do plugin foi atualizada
        pdrActivate(); // Reativação para atualizar a versão

        // Adicione aqui outras tarefas necessárias para a atualização
        update_option( 'pdr_version', PDR_VERSION ); // Atualiza a versão do plugin no banco de dados
    }
}




function pdrStartSession() {
    if (!session_id()) {
        session_start();
    }
}




?>
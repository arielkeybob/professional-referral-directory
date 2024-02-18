<?php
defined('ABSPATH') or die('No script kiddies please!');

global $wpdb;

// Função para criar a tabela de dados de pesquisa



// Função para criar a tabela de contatos
function pdrCreateContactsTable() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'pdr_contacts';
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE IF NOT EXISTS $table_name (
        contact_id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        email VARCHAR(255) NOT NULL UNIQUE,
        default_name VARCHAR(255) DEFAULT NULL
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}


function pdrCreateSearchDataTable() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'pdr_search_data';
    $charset_collate = $wpdb->get_charset_collate();

    // Certifique-se de que a tabela de contatos seja criada primeiro.
    pdrCreateContactsTable(); // Chame a função para criar a tabela de contatos aqui.

    $sql = "CREATE TABLE IF NOT EXISTS $table_name (
        id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        service_type VARCHAR(255) NOT NULL,
        service_location VARCHAR(255),
        search_date DATETIME NOT NULL,
        service_id BIGINT UNSIGNED,
        author_id BIGINT UNSIGNED,
        contact_id BIGINT UNSIGNED,
        search_status VARCHAR(100) NOT NULL DEFAULT 'pending',
        FOREIGN KEY (contact_id) REFERENCES {$wpdb->prefix}pdr_contacts(contact_id) ON DELETE SET NULL
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}



// Função para criar a tabela de relação autor-contato
function pdrCreateAuthorContactRelationsTable() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'pdr_author_contact_relations';
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE IF NOT EXISTS $table_name (
        author_contact_id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        contact_id BIGINT UNSIGNED NOT NULL,
        author_id BIGINT UNSIGNED NOT NULL,
        status VARCHAR(100) NOT NULL,
        custom_name VARCHAR(255) DEFAULT NULL,
        UNIQUE (contact_id, author_id)
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}

// Função para criar a tabela de relação pesquisa-contato
/*
function pdrCreateSearchContactRelationsTable() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'pdr_search_contact_relations';
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE IF NOT EXISTS $table_name (
        search_contact_id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        search_id BIGINT UNSIGNED NOT NULL,
        contact_id BIGINT UNSIGNED NOT NULL,
        status VARCHAR(100) NOT NULL,
        FOREIGN KEY (search_id) REFERENCES {$wpdb->prefix}pdr_search_data(id) ON DELETE CASCADE,
        FOREIGN KEY (contact_id) REFERENCES {$wpdb->prefix}pdr_contacts(contact_id) ON DELETE CASCADE
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}
*/

// Função de ativação do plugin que chama as funções de criação das tabelas
function pdrActivatePlugin() {
    pdrCreateSearchDataTable();
    pdrCreateContactsTable();
    pdrCreateAuthorContactRelationsTable();
    //pdrCreateSearchContactRelationsTable();
    pdrCheckVersion();
    pdrStartSession();
}

register_activation_hook(__FILE__, 'pdrActivatePlugin');

// Verificação e atualização da versão do plugin
function pdrCheckVersion() {
    if (get_option('pdr_version') !== PDR_VERSION) {
        // Atualizações necessárias para a nova versão do plugin
        update_option('pdr_version', PDR_VERSION);
    }
}

// Inicia a sessão PHP se necessário
function pdrStartSession() {
    if (!session_id()) {
        session_start();
    }
}
add_action('init', 'pdrStartSession');
?>

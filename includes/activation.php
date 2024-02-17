<?php
defined('ABSPATH') or die('No script kiddies please!');

// Função para criar a tabela de dados de pesquisa
function pdrCreateSearchDataTable() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'pdr_search_data';
    $contacts_table_name = $wpdb->prefix . 'pdr_contacts'; // Nome da tabela de contatos para referência de chave estrangeira
    $charset_collate = $wpdb->get_charset_collate();

    // Atualize a definição da tabela para incluir a coluna contact_id
    // Adicione também uma chave estrangeira referenciando a tabela wp_pdr_contacts, se desejar garantir integridade referencial
    $sql = "CREATE TABLE IF NOT EXISTS $table_name (
        id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        service_type VARCHAR(255) NOT NULL,
        name VARCHAR(255),
        service_location VARCHAR(255),
        search_date DATETIME NOT NULL,
        service_id BIGINT UNSIGNED,
        author_id BIGINT UNSIGNED,
        contact_id BIGINT UNSIGNED,
        FOREIGN KEY (contact_id) REFERENCES $contacts_table_name(contact_id) ON DELETE SET NULL
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}


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

// Função para criar a tabela de relação contato-autor
function pdrCreateContactAuthorRelationTable() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'pdr_contact_author_relation';
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE IF NOT EXISTS $table_name (
        relation_id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        contact_id BIGINT UNSIGNED NOT NULL,
        author_id BIGINT UNSIGNED NOT NULL,
        post_id BIGINT UNSIGNED NOT NULL,
        status VARCHAR(100) NOT NULL,
        custom_name VARCHAR(255) DEFAULT NULL,
        FOREIGN KEY (contact_id) REFERENCES {$wpdb->prefix}pdr_contacts(contact_id) ON DELETE CASCADE
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}

// Função para verificar a versão do plugin e aplicar atualizações necessárias
function pdrCheckVersion() {
    if (get_option('pdr_version') !== PDR_VERSION) {
        // Atualizações necessárias para a nova versão do plugin
        update_option('pdr_version', PDR_VERSION);
    }
}

// Função para iniciar a sessão, se necessário
function pdrStartSession() {
    if (!session_id()) {
        session_start();
    }
}

// Função de ativação do plugin



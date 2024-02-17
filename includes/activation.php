<?php
defined('ABSPATH') or die('No script kiddies please!');

function pdrCreateSearchDataTable() {
    global $wpdb;
    $charset_collate = $wpdb->get_charset_collate();
    $table_name = $wpdb->prefix . 'pdr_search_data';

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

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}

function pdrCreateContactsTable() {
    global $wpdb;
    $charset_collate = $wpdb->get_charset_collate();
    $table_name = $wpdb->prefix . 'pdr_contacts';

    $sql = "CREATE TABLE IF NOT EXISTS $table_name (
        contact_id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        email VARCHAR(255) NOT NULL UNIQUE,
        default_name VARCHAR(255) DEFAULT NULL
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}

function pdrCreateContactAuthorRelationTable() {
    global $wpdb;
    $charset_collate = $wpdb->get_charset_collate();
    $table_name = $wpdb->prefix . 'pdr_contact_author_relation';

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

function pdr_setup_activation() {
    pdrCreateSearchDataTable();
    pdrCreateContactsTable();
    pdrCreateContactAuthorRelationTable();
    // Atualize aqui com qualquer outra lógica de ativação necessária
}



// Certifique-se de substituir 'PDR_MAIN_FILE' pela constante correta que aponta para o arquivo principal do seu plugin
// Se 'PDR_MAIN_FILE' não for definida, você deve definir ou substituir diretamente pelo caminho do arquivo principal do plugin.
register_activation_hook(PDR_MAIN_FILE, 'pdr_setup_activation');


<?php
defined('ABSPATH') or die('No script kiddies please!');

// Verificações de dependências e ambiente
function pdr_check_environment() {
    global $wp_version;

    // Verifica a versão do PHP.
    if (version_compare(PHP_VERSION, '7.1', '<')) {
        deactivate_plugins(plugin_basename(__FILE__));
        wp_die('Este plugin requer pelo menos a versão 7.1 do PHP para funcionar corretamente. Atualize a versão do PHP ou contacte o administrador do seu servidor.');
    }

    // Verifica a versão do WordPress.
    if (version_compare($wp_version, '5.5', '<')) {
        deactivate_plugins(plugin_basename(__FILE__));
        wp_die('Este plugin requer pelo menos a versão 5.5 do WordPress. Atualize o WordPress para utilizar este plugin.');
    }
}

// Função para limpar todos os transients
function pdr_clear_transients() {
    global $wpdb;

    // Limpa transients com expiração
    $wpdb->query("DELETE FROM `{$wpdb->options}` WHERE `option_name` LIKE ('%\_transient\_%')");

    // Limpa transients sem expiração (opções não expiradas)
    $wpdb->query("DELETE FROM `{$wpdb->options}` WHERE `option_name` LIKE ('%\_transient\_timeout\_%')");
}

function pdr_initialize_user_roles() {
    if (class_exists('PDR_Users')) {
        PDR_Users::initialize_user_roles();
    }
}

global $wpdb;

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

// Função para criar a tabela de dados de pesquisa
function pdrCreateSearchDataTable() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'pdr_search_data';
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE IF NOT EXISTS $table_name (
        id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        service_type VARCHAR(255) NOT NULL,
        service_location VARCHAR(255),
        search_date DATETIME NOT NULL,
        service_id BIGINT UNSIGNED,
        author_id BIGINT UNSIGNED,
        contact_id BIGINT UNSIGNED,
        search_status VARCHAR(100) NOT NULL DEFAULT 'pending',
        FOREIGN KEY (contact_id) REFERENCES {$wpdb->prefix}pdr_contacts(contact_id) ON DELETE SET NULL,
        FOREIGN KEY (service_id) REFERENCES {$wpdb->posts}(ID) ON DELETE CASCADE
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

// Função de ativação do plugin que chama as funções de criação das tabelas
function pdrActivatePlugin() {
    pdr_check_environment();
    pdr_initialize_user_roles();
    wp_cache_flush();
    pdr_clear_transients(); // Limpa todos os transients
    pdrCreateContactsTable();
    pdrCreateSearchDataTable();
    pdrCreateAuthorContactRelationsTable();
    pdrCheckVersion();
    pdrStartSession();
}

register_activation_hook(PDR_MAIN_FILE, 'pdrActivatePlugin');

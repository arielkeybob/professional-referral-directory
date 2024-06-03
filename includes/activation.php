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

function pdrCreateInquiryDataTable() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'pdr_inquiry_data';
    $charset_collate = $wpdb->get_charset_collate();

    // Certifique-se de que a tabela de contatos seja criada primeiro.
    pdrCreateContactsTable(); // Chame a função para criar a tabela de contatos aqui.

    // Verifica se as tabelas não existem antes de criar a nova tabela
    $old_table_name = $wpdb->prefix . 'pdr_search_data';
    if ($wpdb->get_var("SHOW TABLES LIKE '$old_table_name'") != $old_table_name && $wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
        $sql = "CREATE TABLE IF NOT EXISTS $table_name (
            id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            service_type VARCHAR(255) NOT NULL,
            service_location VARCHAR(255),
            inquiry_date DATETIME NOT NULL,
            service_id BIGINT UNSIGNED,
            author_id BIGINT UNSIGNED,
            contact_id BIGINT UNSIGNED,
            inquiry_status VARCHAR(100) NOT NULL DEFAULT 'pending',
            referral_fee_value_view DECIMAL(10, 2) DEFAULT 0.00,
            referral_fee_value_approval DECIMAL(10, 2) DEFAULT 0.00,
            is_paid BOOLEAN DEFAULT FALSE,
            FOREIGN KEY (contact_id) REFERENCES {$wpdb->prefix}pdr_contacts(contact_id) ON DELETE SET NULL,
            FOREIGN KEY (service_id) REFERENCES {$wpdb->posts}(ID) ON DELETE CASCADE
        ) $charset_collate;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }
}

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
        UNIQUE (contact_id, author_id),
        FOREIGN KEY (contact_id) REFERENCES {$wpdb->prefix}pdr_contacts(contact_id) ON DELETE CASCADE,
        FOREIGN KEY (author_id) REFERENCES {$wpdb->users}(ID) ON DELETE CASCADE
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}

// Verificação e atualização da versão do plugin
if (!function_exists('pdrCheckVersion')) {
    function pdrCheckVersion() {
        if (get_option('pdr_version') !== PDR_VERSION) {
            // Atualizações necessárias para a nova versão do plugin
            update_option('pdr_version', PDR_VERSION);
        }
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
    pdr_rename_table(); // Renomeia a tabela, se necessário
    pdrCreateInquiryDataTable();
    pdrCreateAuthorContactRelationsTable();
    pdrCheckVersion();
    pdrStartSession();

    // Defina uma opção para indicar que o plugin foi ativado
    add_option('pdr_plugin_activated', true);
}

register_activation_hook(__FILE__, 'pdrActivatePlugin');

// Redirecionamento para a página de boas-vindas após a ativação do plugin
add_action('admin_init', 'pdr_plugin_redirect_welcome');
function pdr_plugin_redirect_welcome() {
    // Verifique se a opção existe e, em caso afirmativo, redirecione para a página de boas-vindas
    if (get_option('pdr_plugin_activated', false)) {
        delete_option('pdr_plugin_activated'); // Remova a opção para evitar redirecionamentos futuros
        wp_redirect(admin_url('edit.php?post_type=pdr_service&page=pdr-welcome-page'));
        exit;
    }
}

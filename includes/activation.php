<?php
defined('ABSPATH') or die('No script kiddies please!');

// Verificações de dependências e ambiente
function rhb_check_environment() {
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
function rhb_clear_transients() {
    global $wpdb;

    // Limpa transients com expiração
    $wpdb->query("DELETE FROM `{$wpdb->options}` WHERE `option_name` LIKE ('%\_transient\_%')");

    // Limpa transients sem expiração (opções não expiradas)
    $wpdb->query("DELETE FROM `{$wpdb->options}` WHERE `option_name` LIKE ('%\_transient\_timeout\_%')");
}

function rhb_initialize_user_roles() {
    if (class_exists('RHB_Users')) {
        RHB_Users::initialize_user_roles();
    }
}

global $wpdb;

// Função para criar a tabela de contatos
function rhbCreateContactsTable() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'rhb_contacts';
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE IF NOT EXISTS $table_name (
        contact_id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        email VARCHAR(255) NOT NULL UNIQUE,
        default_name VARCHAR(255) DEFAULT NULL
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}

function rhbCreateInquiryDataTable() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'rhb_inquiry_data';
    $charset_collate = $wpdb->get_charset_collate();

    // Certifique-se de que a tabela de contatos seja criada primeiro.
    rhbCreateContactsTable(); // Chame a função para criar a tabela de contatos aqui.

    // Verifica se as tabelas não existem antes de criar a nova tabela
    $old_table_name = $wpdb->prefix . 'rhb_search_data';
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
            referral_fee_value_agreement_reached DECIMAL(10, 2) DEFAULT 0.00,
            is_paid BOOLEAN DEFAULT FALSE,
            FOREIGN KEY (contact_id) REFERENCES {$wpdb->prefix}rhb_contacts(contact_id) ON DELETE SET NULL,
            FOREIGN KEY (service_id) REFERENCES {$wpdb->posts}(ID) ON DELETE CASCADE
        ) $charset_collate;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }
}

function rhbCreateAuthorContactRelationsTable() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'rhb_author_contact_relations';
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE IF NOT EXISTS $table_name (
        author_contact_id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        contact_id BIGINT UNSIGNED NOT NULL,
        author_id BIGINT UNSIGNED NOT NULL,
        status VARCHAR(100) NOT NULL,
        custom_name VARCHAR(255) DEFAULT NULL,
        UNIQUE (contact_id, author_id),
        FOREIGN KEY (contact_id) REFERENCES {$wpdb->prefix}rhb_contacts(contact_id) ON DELETE CASCADE,
        FOREIGN KEY (author_id) REFERENCES {$wpdb->users}(ID) ON DELETE CASCADE
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}

// Verificação e atualização da versão do plugin
if (!function_exists('rhbCheckVersion')) {
    function rhbCheckVersion() {
        if (get_option('rhb_version') !== RHB_VERSION) {
            // Atualizações necessárias para a nova versão do plugin
            update_option('rhb_version', RHB_VERSION);
        }
    }
}

// Inicia a sessão PHP se necessário
function rhbStartSession() {
    if (!session_id()) {
        session_start();
    }
}
add_action('init', 'rhbStartSession');

// Função de ativação do plugin que chama as funções de criação das tabelas
function rhbActivatePlugin() {
    rhb_check_environment();
    rhb_initialize_user_roles();
    wp_cache_flush();
    rhb_clear_transients(); // Limpa todos os transients
    rhb_rename_table(); // Renomeia a tabela, se necessário
    rhbCreateInquiryDataTable();
    rhbCreateAuthorContactRelationsTable();
    rhbCheckVersion();
    rhbStartSession();

    // Defina uma opção para indicar que o plugin foi ativado
    add_option('rhb_plugin_activated', true);
}

register_activation_hook(__FILE__, 'rhbActivatePlugin');

// Redirecionamento para a página de boas-vindas após a ativação do plugin
add_action('admin_init', 'rhb_plugin_redirect_welcome');
function rhb_plugin_redirect_welcome() {
    if (get_option('rhb_plugin_activated', false)) {
        delete_option('rhb_plugin_activated');
        wp_redirect(admin_url('admin.php?page=rhb-setup-wizard'));
        exit;
    }
}

?>

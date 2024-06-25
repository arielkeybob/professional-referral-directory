<?php
defined('ABSPATH') or die('No script kiddies please!');

function rhb_check_environment() {
    global $wp_version;

    if (version_compare(PHP_VERSION, '7.1', '<')) {
        deactivate_plugins(plugin_basename(__FILE__));
        wp_die('Este plugin requer pelo menos a versão 7.1 do PHP para funcionar corretamente. Atualize a versão do PHP ou contacte o administrador do seu servidor.');
    }

    if (version_compare($wp_version, '5.5', '<')) {
        deactivate_plugins(plugin_basename(__FILE__));
        wp_die('Este plugin requer pelo menos a versão 5.5 do WordPress. Atualize o WordPress para utilizar este plugin.');
    }
}

function rhb_clear_transients() {
    global $wpdb;
    $wpdb->query("DELETE FROM `{$wpdb->options}` WHERE `option_name` LIKE ('%\_transient\_%')");
    $wpdb->query("DELETE FROM `{$wpdb->options}` WHERE `option_name` LIKE ('%\_transient\_timeout\_%')");
}

function rhb_initialize_user_roles() {
    // Initialize user roles here, if necessary
}

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

function rhbCreateInquiryDataTable() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'rhb_inquiry_data';
    $charset_collate = $wpdb->get_charset_collate();

    rhbCreateContactsTable(); // Ensure contact table is created first

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
        invoiced BOOLEAN NOT NULL DEFAULT FALSE,
        FOREIGN KEY (contact_id) REFERENCES {$wpdb->prefix}rhb_contacts(contact_id) ON DELETE SET NULL,
        FOREIGN KEY (service_id) REFERENCES {$wpdb->posts}(ID) ON DELETE CASCADE
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}

function rhbCreateInvoicesTable() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'rhb_invoices';
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE IF NOT EXISTS $table_name (
        invoice_id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        provider_id BIGINT UNSIGNED NOT NULL,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        total DECIMAL(10, 2) DEFAULT 0.00,
        is_paid BOOLEAN DEFAULT FALSE,
        FOREIGN KEY (provider_id) REFERENCES {$wpdb->prefix}users(ID) ON DELETE CASCADE
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}

function rhbCreateInvoiceInquiriesTable() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'rhb_invoice_inquiries';
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE IF NOT EXISTS $table_name (
        invoice_id BIGINT UNSIGNED NOT NULL,
        inquiry_id BIGINT UNSIGNED NOT NULL,
        PRIMARY KEY (invoice_id, inquiry_id),
        FOREIGN KEY (invoice_id) REFERENCES {$wpdb->prefix}rhb_invoices(invoice_id) ON DELETE CASCADE,
        FOREIGN KEY (inquiry_id) REFERENCES {$wpdb->prefix}rhb_inquiry_data(id) ON DELETE CASCADE
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}

function rhbActivatePlugin() {
    rhb_check_environment();
    rhb_initialize_user_roles();
    wp_cache_flush();
    rhb_clear_transients();
    rhbCreateContactsTable();
    rhbCreateInquiryDataTable();
    rhbCreateAuthorContactRelationsTable();
    rhbCreateInvoicesTable();
    rhbCreateInvoiceInquiriesTable();

    // Define an option to indicate that the plugin was activated
    add_option('rhb_plugin_activated', true);
}

register_activation_hook(__FILE__, 'rhbActivatePlugin');

function rhb_plugin_redirect_welcome() {
    if (get_option('rhb_plugin_activated', false)) {
        delete_option('rhb_plugin_activated');
        wp_redirect(admin_url('admin.php?page=rhb-setup-wizard'));
        exit;
    }
}
add_action('admin_init', 'rhb_plugin_redirect_welcome');

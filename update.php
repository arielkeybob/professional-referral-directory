<?php
defined('ABSPATH') or die('No script kiddies please!');

// Função para renomear a tabela e colunas antigas
function pdr_rename_table() {
    global $wpdb;
    $old_table_name = $wpdb->prefix . 'pdr_search_data';
    $new_table_name = $wpdb->prefix . 'pdr_inquiry_data';

    // Verifica se a tabela antiga existe e se a nova tabela não existe
    if ($wpdb->get_var("SHOW TABLES LIKE '$old_table_name'") == $old_table_name && $wpdb->get_var("SHOW TABLES LIKE '$new_table_name'") != $new_table_name) {
        // Renomeia a tabela
        $wpdb->query("RENAME TABLE $old_table_name TO $new_table_name");
        
        // Renomeia as colunas
        $wpdb->query("ALTER TABLE $new_table_name CHANGE COLUMN search_date inquiry_date DATETIME NOT NULL");
        $wpdb->query("ALTER TABLE $new_table_name CHANGE COLUMN search_status inquiry_status VARCHAR(100) NOT NULL DEFAULT 'pending'");
        $wpdb->query("ALTER TABLE $new_table_name CHANGE COLUMN commission_value_view referral_fee_value_view DECIMAL(10, 2) DEFAULT 0.00");
        $wpdb->query("ALTER TABLE $new_table_name CHANGE COLUMN commission_value_approval referral_fee_value_approval DECIMAL(10, 2) DEFAULT 0.00");
    }
}

// Função para verificar e executar atualizações
function pdr_update_plugin() {
    $current_version = get_option('pdr_plugin_version', '1.0.0');
    $new_version = '1.1.0';

    if (version_compare($current_version, $new_version, '<')) {
        // Execute o código de atualização necessário
        pdr_rename_table();
        
        // Atualize a versão do plugin no banco de dados
        update_option('pdr_plugin_version', $new_version);
    }
}
add_action('admin_init', 'pdr_update_plugin');

       
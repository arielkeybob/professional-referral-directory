<?php
// Se o arquivo for chamado diretamente, aborta.
if (!defined('WP_UNINSTALL_PLUGIN')) {
    die;
}

global $wpdb;

// Verifica a opção do usuário para saber se deve deletar os dados
$delete_data = get_option('pdr_delete_data_on_uninstall', 'no');

if ($delete_data === 'yes') {
    // Deleta as tabelas personalizadas do plugin
    $tables_to_drop = [
        "{$wpdb->prefix}pdr_search_data",
        "{$wpdb->prefix}pdr_author_contact_relations",
        "{$wpdb->prefix}pdr_contacts"
    ];

    foreach ($tables_to_drop as $table) {
        $wpdb->query("DROP TABLE IF EXISTS {$table}");
    }

    // Deleta as opções do plugin
    $options_to_delete = [
        'pdr_version',
        'pdr_delete_data_on_uninstall',
        'pdr_commission_type',
        'pdr_general_commission_view',
        'pdr_general_commission_approval'
        // Adicione outras opções que o plugin cria, se houver.
    ];

    foreach ($options_to_delete as $option) {
        delete_option($option);
    }

    // Potencialmente, você pode querer deletar metadados do usuário relacionados ao plugin
    $meta_keys_to_delete = [
        'pdr_commission_type',
        'pdr_commission_view',
        'pdr_commission_approval',
        'pdr_override_commission',
        'pdr_override_commission'
    ];

    foreach ($meta_keys_to_delete as $meta_key) {
        $wpdb->delete($wpdb->usermeta, ['meta_key' => $meta_key]);
    }
}
?>

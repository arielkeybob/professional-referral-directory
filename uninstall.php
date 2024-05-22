<?php
// Se o arquivo for chamado diretamente, aborta.
if (!defined('WP_UNINSTALL_PLUGIN')) {
    die;
}

// Define a constante PDR_MAIN_FILE
if (!defined('PDR_MAIN_FILE')) {
    define('PDR_MAIN_FILE', __FILE__);
}

// Carrega o arquivo principal do plugin para acessar as constantes e funções do plugin
require_once plugin_dir_path(__FILE__) . 'includes/activation.php';

// Verifica a opção do usuário para saber se deve deletar os dados
$delete_data = get_option('pdr_delete_data_on_uninstall', 'no');

if ($delete_data === 'yes') {
    global $wpdb;

    // Deleta as tabelas personalizadas do plugin
    
    $wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}pdr_search_data");
    $wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}pdr_author_contact_relations");
    $wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}pdr_contacts");

    // Deleta as opções do plugin
    delete_option('pdr_version');
    delete_option('pdr_delete_data_on_uninstall');
    // Adicione outras opções que o plugin cria, se houver.
}
?>

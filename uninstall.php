<?php
if (!defined('WP_UNINSTALL_PLUGIN')) {
    die;
}

global $wpdb;

// Deleta tabelas personalizadas
$tables_to_drop = [
    "{$wpdb->prefix}rhb_inquiry_data",
    "{$wpdb->prefix}rhb_author_contact_relations",
    "{$wpdb->prefix}rhb_contacts"
];

foreach ($tables_to_drop as $table) {
    $wpdb->query("DROP TABLE IF EXISTS {$table}");
}

// Deleta opções independentes
$independent_options = [
    'rhb_db_version',
    'rhb_plugin_version'
];

foreach ($independent_options as $option) {
    delete_option($option);
}

// Deleta sub-configurações armazenadas em rhb_settings
$settings = get_option('rhb_settings', []);
foreach (array_keys($settings) as $setting_key) {
    unset($settings[$setting_key]);
}
update_option('rhb_settings', $settings); // Atualizar a opção sem as sub-configurações

delete_option('rhb_settings'); // Remover a opção rhb_settings após limpar as sub-configurações

// Potencialmente, você pode querer deletar metadados do usuário relacionados ao plugin
$meta_keys_to_delete = [
    'rhb_referral_fee_type',
    'rhb_referral_fee_view',
    'rhb_referral_fee_agreement_reached',
    'rhb_override_referral_fee',
    'rhb_notifications_closed'
];

foreach ($meta_keys_to_delete as $meta_key) {
    $wpdb->delete($wpdb->usermeta, ['meta_key' => $meta_key]);
}
?>

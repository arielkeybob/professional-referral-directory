<?php
// Se o arquivo for chamado diretamente, aborta.
if (!defined('WP_UNINSTALL_PLUGIN')) {
    die;
}

global $wpdb;

// Verifica a opção do usuário para saber se deve deletar os dados
$options = get_option('rhb_settings', []);
$delete_data = isset($options['rhb_delete_data_on_uninstall']) ? $options['rhb_delete_data_on_uninstall'] : 'no';


if ($delete_data === 'yes') {
    // Deleta as tabelas personalizadas do plugin
    $tables_to_drop = [
        "{$wpdb->prefix}rhb_inquiry_data",
        "{$wpdb->prefix}rhb_author_contact_relations",
        "{$wpdb->prefix}rhb_contacts"
    ];

    foreach ($tables_to_drop as $table) {
        $wpdb->query("DROP TABLE IF EXISTS {$table}");
    }

    // Deleta as opções do plugin
    $options_to_delete = [
        'rhb_version',
        'rhb_delete_data_on_uninstall',
        'rhb_referral_fee_type',
        'rhb_general_referral_fee_view',
        'rhb_general_referral_fee_agreement_reached',
        'rhb_google_maps_api_key',
        'rhb_selected_admins',
        'rhb_manual_emails',
        'rhb_button_color',
        'rhb_button_text_color',
        'rhb_button_hover_color',
        'rhb_button_text_hover_color',
        'rhb_title_font_family',
        'rhb_title_color',
        'rhb_body_font_family',
        'rhb_body_color',
        'rhb_template_choice',
        'rhb_primary_color',
        'rhb_secondary_color',
        'rhb_text_color',
        'rhb_accent_color',
        'rhb_panel_logo',
        'rhb_export_data'
    ];

    foreach ($options_to_delete as $option) {
        delete_option($option);
    }

    // Potencialmente, você pode querer deletar metadados do usuário relacionados ao plugin
    $meta_keys_to_delete = [
        'rhb_referral_fee_type',
        'rhb_referral_fee_view',
        'rhb_referral_fee_agreement_reached',
        'rhb_override_referral_fee',
    ];

    foreach ($meta_keys_to_delete as $meta_key) {
        $wpdb->delete($wpdb->usermeta, ['meta_key' => $meta_key]);
    }
}
?>

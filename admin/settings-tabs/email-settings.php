<?php
// Verifique se este arquivo não está sendo acessado diretamente.
if (!defined('WPINC')) {
    die;
}

// Funções de callback para as configurações de e-mail
function selected_admins_callback() {
    $selected_admins = get_option('myplugin_selected_admins', []);
    $admins = get_users(['role' => 'administrator']);

    echo '<select multiple name="myplugin_selected_admins[]" style="width: 100%;">';
    foreach ($admins as $admin) {
        $selected = in_array($admin->user_email, $selected_admins) ? 'selected' : '';
        $admin_display = sprintf('%s (%s)', $admin->display_name, $admin->user_email);
        echo '<option value="' . esc_attr($admin->user_email) . '" ' . $selected . '>' . esc_html($admin_display) . '</option>';
    }
    echo '</select>';
}

function manual_emails_callback() {
    $manual_emails = get_option('myplugin_manual_emails', '');
    echo "<input type='text' name='myplugin_manual_emails' value='" . esc_attr($manual_emails) . "' style='width: 50%;' placeholder='" . esc_attr__('email1@example.com, email2@example.com', 'professionaldirectory') . "' />";
    echo "<p>" . esc_html__('Enter additional emails separated by commas.', 'professionaldirectory') . "</p>";
}

// Registrando as configurações de e-mail e adicionando os campos
function register_email_settings() {
    register_setting('myplugin_settings_group', 'myplugin_selected_admins');
    register_setting('myplugin_settings_group', 'myplugin_manual_emails');

    add_settings_section(
        'myplugin_email_settings_section',
        __('Email Settings', 'professionaldirectory'),
        null,
        'myplugin'
    );

    add_settings_field(
        'myplugin_selected_admins',
        __('Admins to Receive Emails', 'professionaldirectory'),
        'selected_admins_callback',
        'myplugin',
        'myplugin_email_settings_section'
    );

    add_settings_field(
        'myplugin_manual_emails',
        __('Additional Emails', 'professionaldirectory'),
        'manual_emails_callback',
        'myplugin',
        'myplugin_email_settings_section'
    );
}

// Certifique-se de chamar a função de registro no momento apropriado
add_action('admin_init', 'register_email_settings');

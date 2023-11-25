<?php
class MyPlugin_Admin {

    public function __construct() {
        require_once plugin_dir_path(__FILE__) . '/export-services.php';

        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('admin_init', array($this, 'register_settings'));
    }

    public function add_admin_menu() {
        add_menu_page(
            'ProfessionalDirectory',
            'ProfessionalDirectory',
            'manage_options',
            'professional-directory',
            array($this, 'main_page_callback'),
            'dashicons-admin-generic',
            6
        );

        $all_services_url = admin_url('edit.php?post_type=professional_service');
        add_submenu_page(
            'professional-directory',
            'Manage Services',
            'Manage Services',
            'manage_options',
            $all_services_url
        );

        $service_type_url = admin_url('edit-tags.php?taxonomy=service_type&post_type=professional_service');
        add_submenu_page(
            'professional-directory',
            'Service Type',
            'Service Type',
            'manage_options',
            $service_type_url
        );

        add_submenu_page(
            'professional-directory',
            'Settings',
            'Settings',
            'manage_options',
            'professional-directory-settings',
            array($this, 'settings_page')
        );

        add_submenu_page(
            'professional-directory',
            'Exportar Services',
            'Exportar',
            'manage_options',
            'professional-directory-export',
            array($this, 'export_services_callback')
        );
    }

    public function register_settings() {
        register_setting('myplugin_settings_group', 'myplugin_google_maps_api_key');
        register_setting('myplugin_settings_group', 'myplugin_selected_admins');
        register_setting('myplugin_settings_group', 'myplugin_manual_emails');
        
        add_settings_section(
            'myplugin_api_settings_section',
            'API Settings',
            null,
            'myplugin'
        );

        add_settings_field(
            'myplugin_google_maps_api_key',
            'Google Maps API Key',
            array($this, 'google_maps_api_key_callback'),
            'myplugin',
            'myplugin_api_settings_section'
        );

        add_settings_section(
            'myplugin_email_settings_section',
            'Configurações de E-mail',
            null,
            'myplugin'
        );

        add_settings_field(
            'myplugin_selected_admins',
            'Admins para Receber E-mails',
            array($this, 'selected_admins_callback'),
            'myplugin',
            'myplugin_email_settings_section'
        );

        add_settings_field(
            'myplugin_manual_emails',
            'E-mails Adicionais',
            array($this, 'manual_emails_callback'),
            'myplugin',
            'myplugin_email_settings_section'
        );
    }

    public function main_page_callback() {
        echo '<div class="wrap"><h1>Bem-vindo ao ProfessionalDirectory</h1></div>';
    }

    public function settings_page() {
        echo '<div class="wrap">';
        echo '<h2>Configurações do ProfessionalDirectory</h2>';
        echo '<form method="post" action="options.php">';
        settings_fields('myplugin_settings_group');
        do_settings_sections('myplugin');
        submit_button();
        echo '</form>';
        echo '</div>';
    }

    public function export_services_callback() {
        echo '<div class="wrap">';
        echo '<h1>Exportar Services</h1>';
        echo '<form method="post">';
        echo '<input type="hidden" name="action" value="export_services" />';
        submit_button('Exportar Services para CSV');
        echo '</form>';
        echo '</div>';

        if (isset($_POST['action']) && $_POST['action'] == 'export_services') {
            export_services_to_csv();
        }
    }

    public function google_maps_api_key_callback() {
        $api_key = get_option('myplugin_google_maps_api_key');
        echo "<input type='text' name='myplugin_google_maps_api_key' value='" . esc_attr($api_key) . "' />";
    }

    public function selected_admins_callback() {
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

    public function manual_emails_callback() {
        $manual_emails = get_option('myplugin_manual_emails', '');
        echo "<input type='text' name='myplugin_manual_emails' value='" . esc_attr($manual_emails) . "' style='width: 50%;' placeholder='email1@example.com, email2@example.com' />";
        echo "<p>Insira os e-mails adicionais separados por vírgulas.</p>";
    }
    
}

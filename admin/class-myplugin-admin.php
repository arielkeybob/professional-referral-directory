<?php
class MyPlugin_Admin {

    public function __construct() {
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('admin_init', array($this, 'register_settings'));
    }

    public function add_admin_menu() {
        // Mudando de add_menu_page para add_submenu_page
        add_submenu_page(
            'edit.php?post_type=professional_service',      // Adicionando como um submenu do tipo de post "Service"
            __('General Settings', 'professionaldirectory'),   // Título da página
            __('Settings', 'professionaldirectory'),                            // Título do menu
            'manage_options',                               // Capacidade necessária
            'myplugin',                                     // Slug do menu
            array($this, 'settings_page')                   // Função de callback para renderizar a página
        );
    }
    
    public function settings_page() {
        ?>
        <div class="wrap">
            <h2><?php echo esc_html__('General Settings', 'professionaldirectory'); ?></h2>
            <form method="post" action="options.php">
                <?php
                settings_fields('myplugin_settings_group');
                do_settings_sections('myplugin');
                submit_button();
                ?>
            </form>
        </div>
        <?php
    }
    

    public function register_settings() {
        register_setting('myplugin_settings_group', 'myplugin_google_maps_api_key');
        register_setting('myplugin_settings_group', 'myplugin_selected_admins');
        register_setting('myplugin_settings_group', 'myplugin_manual_emails');
        
        // Seção de Configurações da API
        add_settings_section(
            'myplugin_api_settings_section',
            __('API Settings', 'professionaldirectory'), // Traduzindo o título da seção
            null,
            'myplugin'
        );
        
        add_settings_field(
            'myplugin_google_maps_api_key',
            __('Google Maps API Key', 'professionaldirectory'), // Traduzindo o título do campo
            array($this, 'google_maps_api_key_callback'),
            'myplugin',
            'myplugin_api_settings_section'
        );
        
        // Nova Seção de Configurações de E-mail
        add_settings_section(
            'myplugin_email_settings_section',
            __('Email Settings', 'professionaldirectory'), // Traduzindo o título da seção
            null,
            'myplugin'
        );
        
        add_settings_field(
            'myplugin_selected_admins',
            __('Admins to Receive Emails', 'professionaldirectory'), // Traduzindo o título do campo
            array($this, 'selected_admins_callback'),
            'myplugin',
            'myplugin_email_settings_section'
        );
        
        add_settings_field(
            'myplugin_manual_emails',
            __('Additional Emails', 'professionaldirectory'), // Traduzindo o título do campo
            array($this, 'manual_emails_callback'),
            'myplugin',
            'myplugin_email_settings_section'
        );        
    }
    
    
    public function google_maps_api_key_callback() {
        $api_key = get_option('myplugin_google_maps_api_key');
        echo "<input type='text' name='myplugin_google_maps_api_key' value='" . esc_attr($api_key) . "' placeholder='" . esc_attr__('Enter the Google Maps API Key', 'professionaldirectory') . "' />";
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
        echo "<input type='text' name='myplugin_manual_emails' value='" . esc_attr($manual_emails) . "' style='width: 50%;' placeholder='" . esc_attr__('email1@example.com, email2@example.com', 'professionaldirectory') . "' />";
        echo "<p>" . esc_html__('Enter additional emails separated by commas.', 'professionaldirectory') . "</p>";

    }
    
}

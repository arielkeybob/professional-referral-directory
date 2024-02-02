<?php
class PDR_Settings {

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
        register_setting('myplugin_settings_group', 'myplugin_template_choice');
        
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
        
        // Seção de Configurações de Template
        add_settings_section(
            'myplugin_template_settings_section',
            __('Template Settings', 'professionaldirectory'),
            null,
            'myplugin'
        );
        
        add_settings_field(
            'myplugin_template_choice',
            __('Choose Template', 'professionaldirectory'),
            array($this, 'template_choice_callback'),
            'myplugin',
            'myplugin_template_settings_section'
        );


        add_settings_section(
            'myplugin_frontend_style_settings_section',
            __('Frontend Style Settings', 'professionaldirectory'),
            null,
            'myplugin'
        );

        // Lista de configurações de estilo
        $style_settings = [
            'button_color' => __('Button Color', 'professionaldirectory'),
            'button_text_color' => __('Button Text Color', 'professionaldirectory'),
            'button_hover_color' => __('Button Hover Color', 'professionaldirectory'),
            'button_text_hover_color' => __('Button Text Hover Color', 'professionaldirectory'),
            'title_font_family' => __('Title Font Family', 'professionaldirectory'),
            'title_color' => __('Title Color', 'professionaldirectory'),
            'body_font_family' => __('Body Font Family', 'professionaldirectory'),
            'body_color' => __('Body Color', 'professionaldirectory'),
            // Adicione os outros campos de estilo aqui...
        ];

        foreach ($style_settings as $setting_name => $setting_label) {
            register_setting('myplugin_settings_group', 'myplugin_' . $setting_name);
            add_settings_field(
                'myplugin_' . $setting_name,
                $setting_label,
                array($this, $setting_name . '_callback'),
                'myplugin',
                'myplugin_frontend_style_settings_section'
            );
        }

    }
    

    public function button_color_callback() {
        $value = get_option('myplugin_button_color', '#000000');
        echo "<input type='color' name='myplugin_button_color' value='" . esc_attr($value) . "'>";
    }

    public function button_text_color_callback() {
        $value = get_option('myplugin_button_text_color', '#FFFFFF');
        echo "<input type='color' name='myplugin_button_text_color' value='" . esc_attr($value) . "'>";
    }

    public function button_hover_color_callback() {
        $value = get_option('myplugin_button_hover_color', '#000000');
        echo "<input type='color' name='myplugin_button_hover_color' value='" . esc_attr($value) . "'>";
    }

    public function button_text_hover_color_callback() {
        $value = get_option('myplugin_button_text_hover_color', '#FFFFFF');
        echo "<input type='color' name='myplugin_button_text_hover_color' value='" . esc_attr($value) . "'>";
    }

    public function title_font_family_callback() {
        $value = get_option('myplugin_title_font_family', '');
        echo "<input type='text' name='myplugin_title_font_family' value='" . esc_attr($value) . "' />";
    }

    public function title_color_callback() {
        $value = get_option('myplugin_title_color', '#000000');
        echo "<input type='color' name='myplugin_title_color' value='" . esc_attr($value) . "'>";
    }

    public function body_font_family_callback() {
        $value = get_option('myplugin_body_font_family', '');
        echo "<input type='text' name='myplugin_body_font_family' value='" . esc_attr($value) . "' />";
    }

    public function body_color_callback() {
        $value = get_option('myplugin_body_color', '#000000');
        echo "<input type='color' name='myplugin_body_color' value='" . esc_attr($value) . "'>";
    }



    public function template_choice_callback() {
        $template_choice = get_option('myplugin_template_choice', 'template-1');
        echo "<select name='myplugin_template_choice'>";
        echo "<option value='template-1' " . selected($template_choice, 'template-1', false) . ">" . esc_html__('Template 1', 'professionaldirectory') . "</option>";
        echo "<option value='template-2' " . selected($template_choice, 'template-2', false) . ">" . esc_html__('Template 2', 'professionaldirectory') . "</option>";
        echo "</select>";
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

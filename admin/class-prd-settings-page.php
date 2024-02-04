<?php
class PDR_Settings {

    public function __construct() {
        add_action('admin_init', array($this, 'register_settings'));
    }
    
    public function settings_page() {
        $active_tab = isset($_GET['tab']) ? $_GET['tab'] : 'general_settings';
        ?>
        <div class="wrap">
            <h2><?php echo esc_html__('Settings', 'professionaldirectory'); ?></h2>
            <h2 class="nav-tab-wrapper">
                <a href="?post_type=professional_service&page=myplugin&tab=general_settings" class="nav-tab <?php echo $active_tab == 'general_settings' ? 'nav-tab-active' : ''; ?>"><?php echo esc_html__('General Settings', 'professionaldirectory'); ?></a>
                <a href="?post_type=professional_service&page=myplugin&tab=api_settings" class="nav-tab <?php echo $active_tab == 'api_settings' ? 'nav-tab-active' : ''; ?>"><?php echo esc_html__('API Settings', 'professionaldirectory'); ?></a>
                <a href="?post_type=professional_service&page=myplugin&tab=email_settings" class="nav-tab <?php echo $active_tab == 'email_settings' ? 'nav-tab-active' : ''; ?>"><?php echo esc_html__('Email Settings', 'professionaldirectory'); ?></a>
                <a href="?post_type=professional_service&page=myplugin&tab=style_settings" class="nav-tab <?php echo $active_tab == 'style_settings' ? 'nav-tab-active' : ''; ?>"><?php echo esc_html__('Style Settings', 'professionaldirectory'); ?></a>
            </h2>
            <form method="post" action="options.php">
                <?php
                if ($active_tab == 'general_settings') {
                    settings_fields('myplugin_settings_group');
                    do_settings_sections('myplugin_general_settings');
                } elseif ($active_tab == 'api_settings') {
                    settings_fields('myplugin_settings_group');
                    do_settings_sections('myplugin_api_settings');
                } elseif ($active_tab == 'email_settings') {
                    settings_fields('myplugin_settings_group');
                    do_settings_sections('myplugin_email_settings');
                } elseif ($active_tab == 'style_settings') {
                    settings_fields('myplugin_settings_group');
                    do_settings_sections('myplugin_style_settings');
                }
                submit_button();
                ?>
            </form>
        </div>
        <?php
    }
    

    public function register_settings() {
        // Registra configurações para a aba "General Settings"
        register_setting('myplugin_settings_group_general', 'myplugin_option_general');
    
        add_settings_section(
            'myplugin_general_settings_section', 
            __('General Settings', 'professionaldirectory'), 
            null, 
            'myplugin_general_settings'
        );
    
        add_settings_field(
            'myplugin_general_option', 
            __('General Option', 'professionaldirectory'), 
            array($this, 'general_option_callback'), 
            'myplugin_general_settings', 
            'myplugin_general_settings_section'
        );
    
        // Repita o padrão acima para cada conjunto de configurações/aba.
    
        // Configurações para a aba "API Settings"
        register_setting('myplugin_settings_group_api', 'myplugin_google_maps_api_key');
    
        add_settings_section(
            'myplugin_api_settings_section',
            __('API Settings', 'professionaldirectory'),
            null,
            'myplugin_api_settings'
        );
    
        add_settings_field(
            'myplugin_google_maps_api_key',
            __('Google Maps API Key', 'professionaldirectory'),
            array($this, 'google_maps_api_key_callback'),
            'myplugin_api_settings',
            'myplugin_api_settings_section'
        );
    
        // Configurações para a aba "Email Settings"
        register_setting('myplugin_settings_group_email', 'myplugin_selected_admins');
        register_setting('myplugin_settings_group_email', 'myplugin_manual_emails');
    
        add_settings_section(
            'myplugin_email_settings_section',
            __('Email Settings', 'professionaldirectory'),
            null,
            'myplugin_email_settings'
        );
    
        // Campos para "Email Settings"
        add_settings_field(
            'myplugin_selected_admins',
            __('Admins to Receive Emails', 'professionaldirectory'),
            array($this, 'selected_admins_callback'),
            'myplugin_email_settings',
            'myplugin_email_settings_section'
        );
    
        add_settings_field(
            'myplugin_manual_emails',
            __('Additional Emails', 'professionaldirectory'),
            array($this, 'manual_emails_callback'),
            'myplugin_email_settings',
            'myplugin_email_settings_section'
        );
    


        register_setting('myplugin_settings_group', 'myplugin_button_color', 'sanitize_hex_color');

        // Configurações para a aba "Style Settings"
        // Configurações para a aba "Style Settings"
        add_settings_section(
            'myplugin_style_settings_section', // Correção aqui: ID da seção
            __('Style Settings', 'professionaldirectory'), // Título da seção
            null, // Callback da seção, opcional
            'myplugin_style_settings' // Correção aqui: Deve corresponder ao usado em do_settings_sections
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
                'myplugin_style_settings', // Correção aqui: Deve corresponder ao usado em do_settings_sections
                'myplugin_style_settings_section'
            );
        }
    }
    
    public function general_option_callback() {
        $option_value = get_option('myplugin_general_option', ''); // Use the correct option name
        echo "<input type='text' name='myplugin_general_option' value='" . esc_attr($option_value) . "' />";
    }
    
    

    public function button_color_callback() {
        $value = get_option('myplugin_button_color', '#000000');
        // Campo de entrada de cor original
        echo "<input type='color' name='myplugin_button_color' value='" . esc_attr($value) . "' />";
        // Adicionar campo de entrada de texto para código hexadecimal
        echo "<input type='text' name='myplugin_button_color_hex' value='" . esc_attr($value) . "' placeholder='#ffffff' />";
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
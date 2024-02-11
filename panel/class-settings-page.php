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
                <a href="?post_type=professional_service&page=settings&tab=general_settings" class="nav-tab <?php echo $active_tab == 'general_settings' ? 'nav-tab-active' : ''; ?>"><?php echo esc_html__('General Settings', 'professionaldirectory'); ?></a>
                <a href="?post_type=professional_service&page=settings&tab=api_settings" class="nav-tab <?php echo $active_tab == 'api_settings' ? 'nav-tab-active' : ''; ?>"><?php echo esc_html__('API Settings', 'professionaldirectory'); ?></a>
                <a href="?post_type=professional_service&page=settings&tab=email_settings" class="nav-tab <?php echo $active_tab == 'email_settings' ? 'nav-tab-active' : ''; ?>"><?php echo esc_html__('Email Settings', 'professionaldirectory'); ?></a>
                <a href="?post_type=professional_service&page=settings&tab=style_settings" class="nav-tab <?php echo $active_tab == 'style_settings' ? 'nav-tab-active' : ''; ?>"><?php echo esc_html__('Frontend Style', 'professionaldirectory'); ?></a>
                <a href="?post_type=professional_service&page=settings&tab=panel_style" class="nav-tab <?php echo $active_tab == 'panel_style' ? 'nav-tab-active' : ''; ?>"><?php echo esc_html__('Panel Style', 'professionaldirectory'); ?></a>

            </h2>
            <form method="post" action="options.php">
                <?php
                if ($active_tab == 'general_settings') {
                    settings_fields('prd_settings_group_general');
                    do_settings_sections('prd_general_settings');
                } elseif ($active_tab == 'api_settings') {
                    settings_fields('prd_settings_group_api');
                    do_settings_sections('prd_api_settings');
                } elseif ($active_tab == 'email_settings') {
                    settings_fields('prd_settings_group_email');
                    do_settings_sections('prd_email_settings');
                } elseif ($active_tab == 'style_settings') {
                    settings_fields('prd_settings_group_frontend_style'); // Certifique-se de registrar este grupo em register_settings()
                    do_settings_sections('prd_style_settings');
                } elseif ($active_tab == 'panel_style') {
                    settings_fields('prd_settings_group_panel_style');
                    do_settings_sections('prd_panel_style_settings');
                }
                               
                submit_button();
                ?>
            </form>
        </div>
        <?php
    }
    

    public function register_settings() {
        // Registra configurações para a aba "General Settings"
        register_setting('prd_settings_group_general', 'prd_general_option');
        
        add_settings_section(
            'prd_general_settings_section', 
            __('General Settings', 'professionaldirectory'), 
            null, 
            'prd_general_settings'
        );
    
        add_settings_field(
            'prd_general_option', 
            __('General Option', 'professionaldirectory'), 
            array($this, 'general_option_callback'), 
            'prd_general_settings', 
            'prd_general_settings_section'
        );
    




        // Configurações para a aba "API Settings"
        register_setting('prd_settings_group_api', 'prd_google_maps_api_key');
    
        add_settings_section(
            'prd_api_settings_section',
            __('API Settings', 'professionaldirectory'),
            null,
            'prd_api_settings'
        );
    
        add_settings_field(
            'prd_google_maps_api_key',
            __('Google Maps API Key', 'professionaldirectory'),
            array($this, 'google_maps_api_key_callback'),
            'prd_api_settings',
            'prd_api_settings_section'
        );
    




        // Configurações para a aba "Email Settings"
        register_setting('prd_settings_group_email', 'prd_selected_admins');
        register_setting('prd_settings_group_email', 'prd_manual_emails');
    
        add_settings_section(
            'prd_email_settings_section',
            __('Email Settings', 'professionaldirectory'),
            null,
            'prd_email_settings'
        );
    
        add_settings_field(
            'prd_selected_admins',
            __('Admins to Receive Emails', 'professionaldirectory'),
            array($this, 'selected_admins_callback'),
            'prd_email_settings',
            'prd_email_settings_section'
        );
    
        add_settings_field(
            'prd_manual_emails',
            __('Additional Emails', 'professionaldirectory'),
            array($this, 'manual_emails_callback'),
            'prd_email_settings',
            'prd_email_settings_section'
        );
    


        


        // Configurações para a aba "Frontend Style"
        register_setting('prd_settings_group_frontend_style', 'prd_button_color', 'sanitize_hex_color');
        register_setting('prd_settings_group_frontend_style', 'prd_button_text_color', 'sanitize_hex_color');
        register_setting('prd_settings_group_frontend_style', 'prd_button_hover_color', 'sanitize_hex_color');
        register_setting('prd_settings_group_frontend_style', 'prd_button_text_hover_color', 'sanitize_hex_color');
        register_setting('prd_settings_group_frontend_style', 'prd_title_font_family');
        register_setting('prd_settings_group_frontend_style', 'prd_title_color', 'sanitize_hex_color');
        register_setting('prd_settings_group_frontend_style', 'prd_body_font_family');
        register_setting('prd_settings_group_frontend_style', 'prd_body_color', 'sanitize_hex_color');


        add_settings_section(
            'prd_style_settings_section', 
            __('Frontend Style', 'professionaldirectory'), // Título da seção
            null, // Callback da seção, opcional
            'prd_style_settings' // Correção aqui: Deve corresponder ao usado em do_settings_sections
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
            'body_color' => __('Body Color', 'professionaldirectory')
            // Adicione os outros campos de estilo aqui...
        ];

        foreach ($style_settings as $setting_name => $setting_label) {
            register_setting('prd_settings_group', 'prd_' . $setting_name);
            add_settings_field(
                'prd_' . $setting_name,
                $setting_label,
                array($this, $setting_name . '_callback'),
                'prd_style_settings', 
                'prd_style_settings_section'
            );
        }




        // Configurações para a aba "Panel Style"
        register_setting('prd_settings_group_panel_style', 'prd_primary_color', 'sanitize_hex_color');
        register_setting('prd_settings_group_panel_style', 'prd_secondary_color', 'sanitize_hex_color');
        register_setting('prd_settings_group_panel_style', 'prd_text_color', 'sanitize_hex_color');
        register_setting('prd_settings_group_panel_style', 'prd_accent_color', 'sanitize_hex_color');

        // Dentro de register_settings()
        add_settings_section(
            'prd_panel_style_section',
            __('Panel Style Settings', 'professionaldirectory'),
            null,
            'prd_panel_style_settings'
        );

        // Adicionando campos
        add_settings_field(
            'prd_primary_color',
            __('Primary Color', 'professionaldirectory'),
            array($this, 'primary_color_callback'),
            'prd_panel_style_settings',
            'prd_panel_style_section'
        );

        add_settings_field(
            'prd_secondary_color',
            __('Secondary Color', 'professionaldirectory'),
            array($this, 'secondary_color_callback'),
            'prd_panel_style_settings',
            'prd_panel_style_section'
        );

        add_settings_field(
            'prd_text_color',
            __('Text Color', 'professionaldirectory'),
            array($this, 'text_color_callback'),
            'prd_panel_style_settings',
            'prd_panel_style_section'
        );

        add_settings_field(
            'prd_accent_color',
            __('Accent Color', 'professionaldirectory'),
            array($this, 'accent_color_callback'),
            'prd_panel_style_settings',
            'prd_panel_style_section'
        );


    }
    
    //Callback General options
    public function general_option_callback() {
        $option_value = get_option('prd_general_option', ''); // Use the correct option name
        echo "<input type='text' name='prd_general_option' value='" . esc_attr($option_value) . "' />";
    }
    
    


    //Callbacks API Options
    public function google_maps_api_key_callback() {
        $api_key = get_option('prd_google_maps_api_key');
        echo "<input type='text' name='prd_google_maps_api_key' value='" . esc_attr($api_key) . "' placeholder='" . esc_attr__('Enter the Google Maps API Key', 'professionaldirectory') . "' />";
    }





    //Callbacks Email Options
    public function selected_admins_callback() {
        $selected_admins = get_option('prd_selected_admins', []);
        
        // Garante que $selected_admins seja um array
        if (!is_array($selected_admins)) {
            $selected_admins = explode(',', $selected_admins); // Apenas se você suspeitar que o valor possa ser uma string delimitada por vírgulas
        }
        
        $admins = get_users(['role' => 'administrator']);
    
        echo '<select multiple name="prd_selected_admins[]" style="width: 100%;">';
        foreach ($admins as $admin) {
            $selected = in_array($admin->user_email, $selected_admins) ? 'selected' : '';
            $admin_display = sprintf('%s (%s)', $admin->display_name, $admin->user_email);
            echo '<option value="' . esc_attr($admin->user_email) . '" ' . $selected . '>' . esc_html($admin_display) . '</option>';
        }
        echo '</select>';
    }
    
    public function manual_emails_callback() {
        $manual_emails = get_option('prd_manual_emails', '');
        echo "<input type='text' name='prd_manual_emails' value='" . esc_attr($manual_emails) . "' style='width: 50%;' placeholder='" . esc_attr__('email1@example.com, email2@example.com', 'professionaldirectory') . "' />";
        echo "<p>" . esc_html__('Enter additional emails separated by commas.', 'professionaldirectory') . "</p>";
    }




    //Callbacks Frontend Style options
    public function button_color_callback() {
        $value = get_option('prd_button_color', '#000000');
        // Campo de entrada de cor original
        echo "<input type='color' name='prd_button_color' value='" . esc_attr($value) . "' />";
        // Adicionar campo de entrada de texto para código hexadecimal
        echo "<input type='text' name='prd_button_color_hex' value='" . esc_attr($value) . "' placeholder='#ffffff' />";
    }
       
    public function button_text_color_callback() {
        $value = get_option('prd_button_text_color', '#FFFFFF');
        echo "<input type='color' name='prd_button_text_color' value='" . esc_attr($value) . "' />";
        echo "<input type='text' name='prd_button_text_color_hex' value='" . esc_attr($value) . "' placeholder='#ffffff' />";
    }

    public function button_hover_color_callback() {
        $value = get_option('prd_button_hover_color', '#000000');
        echo "<input type='color' name='prd_button_hover_color' value='" . esc_attr($value) . "' />";
        echo "<input type='text' name='prd_button_hover_color_hex' value='" . esc_attr($value) . "' placeholder='#000000' />";
    }

    public function button_text_hover_color_callback() {
    $value = get_option('prd_button_text_hover_color', '#FFFFFF');
    echo "<input type='color' name='prd_button_text_hover_color' value='" . esc_attr($value) . "' />";
    echo "<input type='text' name='prd_button_text_hover_color_hex' value='" . esc_attr($value) . "' placeholder='#ffffff' />";
    }

    public function title_font_family_callback() {
        $value = get_option('prd_title_font_family', '');
        echo "<input type='text' name='prd_title_font_family' value='" . esc_attr($value) . "' />";
    }

    public function title_color_callback() {
        $value = get_option('prd_title_color', '#000000');
        echo "<input type='color' name='prd_title_color' value='" . esc_attr($value) . "' />";
        echo "<input type='text' name='prd_title_color_hex' value='" . esc_attr($value) . "' placeholder='#000000' />";
    }

    public function body_font_family_callback() {
        $value = get_option('prd_body_font_family', '');
        echo "<input type='text' name='prd_body_font_family' value='" . esc_attr($value) . "' />";
    }

    public function body_color_callback() {
        $value = get_option('prd_body_color', '#000000');
        echo "<input type='color' name='prd_body_color' value='" . esc_attr($value) . "' />";
        echo "<input type='text' name='prd_body_color_hex' value='" . esc_attr($value) . "' placeholder='#000000' />";
    }

    public function template_choice_callback() {
        $template_choice = get_option('prd_template_choice', 'template-1');
        echo "<select name='prd_template_choice'>";
        echo "<option value='template-1' " . selected($template_choice, 'template-1', false) . ">" . esc_html__('Template 1', 'professionaldirectory') . "</option>";
        echo "<option value='template-2' " . selected($template_choice, 'template-2', false) . ">" . esc_html__('Template 2', 'professionaldirectory') . "</option>";
        echo "</select>";
    }




    // Callback para Panel Style
    // Callback para a cor primária
    public function primary_color_callback() {
        $value = get_option('prd_primary_color', '#0073aa'); // Valor padrão como exemplo
        echo "<input type='color' name='prd_primary_color' value='" . esc_attr($value) . "' />";
        echo "<input type='text' name='prd_primary_color_hex' value='" . esc_attr($value) . "' placeholder='#0073aa' />";
    }

    // Callback para a cor secundária
    public function secondary_color_callback() {
        $value = get_option('prd_secondary_color', '#0073aa'); // Valor padrão como exemplo
        echo "<input type='color' name='prd_secondary_color' value='" . esc_attr($value) . "' />";
        echo "<input type='text' name='prd_secondary_color_hex' value='" . esc_attr($value) . "' placeholder='#0073aa' />";
    }

    // Callback para a cor do texto
    public function text_color_callback() {
        $value = get_option('prd_text_color', '#333333'); // Valor padrão como exemplo
        echo "<input type='color' name='prd_text_color' value='" . esc_attr($value) . "' />";
        echo "<input type='text' name='prd_text_color_hex' value='" . esc_attr($value) . "' placeholder='#333333' />";
    }

    // Callback para a cor de destaque (Accent color)
    public function accent_color_callback() {
        $value = get_option('prd_accent_color', '#0073aa'); // Valor padrão como exemplo
        echo "<input type='color' name='prd_accent_color' value='" . esc_attr($value) . "' />";
        echo "<input type='text' name='prd_accent_color_hex' value='" . esc_attr($value) . "' placeholder='#0073aa' />";
    }


    
}
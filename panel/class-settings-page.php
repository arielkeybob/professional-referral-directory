<?php
defined('ABSPATH') or die('No script kiddies please!');

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
                <a href="?post_type=professional_service&page=pdr-general-settings&tab=general_settings" class="nav-tab <?php echo $active_tab == 'general_settings' ? 'nav-tab-active' : ''; ?>"><?php echo esc_html__('General Settings', 'professionaldirectory'); ?></a>
                <a href="?post_type=professional_service&page=pdr-general-settings&tab=api_settings" class="nav-tab <?php echo $active_tab == 'api_settings' ? 'nav-tab-active' : ''; ?>"><?php echo esc_html__('API Settings', 'professionaldirectory'); ?></a>
                <a href="?post_type=professional_service&page=pdr-general-settings&tab=email_settings" class="nav-tab <?php echo $active_tab == 'email_settings' ? 'nav-tab-active' : ''; ?>"><?php echo esc_html__('Email Settings', 'professionaldirectory'); ?></a>
                <a href="?post_type=professional_service&page=pdr-general-settings&tab=style_settings" class="nav-tab <?php echo $active_tab == 'style_settings' ? 'nav-tab-active' : ''; ?>"><?php echo esc_html__('Frontend Style', 'professionaldirectory'); ?></a>
                <a href="?post_type=professional_service&page=pdr-general-settings&tab=panel_style" class="nav-tab <?php echo $active_tab == 'panel_style' ? 'nav-tab-active' : ''; ?>"><?php echo esc_html__('Panel Style', 'professionaldirectory'); ?></a>
            </h2>
            <form method="post" action="options.php">
                <?php
                if ($active_tab == 'general_settings') {
                    settings_fields('pdr_settings_group_general');
                    do_settings_sections('pdr_general_settings');
                } elseif ($active_tab == 'api_settings') {
                    settings_fields('pdr_settings_group_api');
                    do_settings_sections('pdr_api_settings');
                } elseif ($active_tab == 'email_settings') {
                    settings_fields('pdr_settings_group_email');
                    do_settings_sections('pdr_email_settings');
                } elseif ($active_tab == 'style_settings') {
                    settings_fields('pdr_settings_group_frontend_style');
                    do_settings_sections('pdr_style_settings');
                } elseif ($active_tab == 'panel_style') {
                    settings_fields('pdr_settings_group_panel_style');
                    do_settings_sections('pdr_panel_style_settings');
                }
                               
                submit_button();
                ?>
            </form>
        </div>
        <?php
    }

    public function register_settings() {
        // Registra configurações para a aba "General Settings"
        register_setting('pdr_settings_group_general', 'pdr_general_option');
        register_setting('pdr_settings_group_general', 'pdr_delete_data_on_uninstall'); // Novo registro para deletar dados
        
        add_settings_section(
            'pdr_general_settings_section', 
            __('General Settings', 'professionaldirectory'), 
            null, 
            'pdr_general_settings'
        );
    
        add_settings_field(
            'pdr_general_option', 
            __('General Option', 'professionaldirectory'), 
            array($this, 'general_option_callback'), 
            'pdr_general_settings', 
            'pdr_general_settings_section'
        );

        // Adiciona a nova configuração para deletar dados na desinstalação
        add_settings_field(
            'pdr_delete_data_on_uninstall',
            __('Delete Data on Uninstall', 'professionaldirectory'),
            array($this, 'delete_data_on_uninstall_callback'),
            'pdr_general_settings',
            'pdr_general_settings_section'
        );

        // Configurações para a aba "API Settings"
        register_setting('pdr_settings_group_api', 'pdr_google_maps_api_key');
    
        add_settings_section(
            'pdr_api_settings_section',
            __('API Settings', 'professionaldirectory'),
            null,
            'pdr_api_settings'
        );
    
        add_settings_field(
            'pdr_google_maps_api_key',
            __('Google Maps API Key', 'professionaldirectory'),
            array($this, 'google_maps_api_key_callback'),
            'pdr_api_settings',
            'pdr_api_settings_section'
        );

        // Configurações para a aba "Email Settings"
        register_setting('pdr_settings_group_email', 'pdr_selected_admins');
        register_setting('pdr_settings_group_email', 'pdr_manual_emails');
    
        add_settings_section(
            'pdr_email_settings_section',
            __('Email Settings', 'professionaldirectory'),
            null,
            'pdr_email_settings'
        );
    
        add_settings_field(
            'pdr_selected_admins',
            __('Admins to Receive Emails', 'professionaldirectory'),
            array($this, 'selected_admins_callback'),
            'pdr_email_settings',
            'pdr_email_settings_section'
        );
    
        add_settings_field(
            'pdr_manual_emails',
            __('Additional Emails', 'professionaldirectory'),
            array($this, 'manual_emails_callback'),
            'pdr_email_settings',
            'pdr_email_settings_section'
        );

        // Configurações para a aba "Frontend Style"
        register_setting('pdr_settings_group_frontend_style', 'pdr_button_color', 'sanitize_hex_color');
        register_setting('pdr_settings_group_frontend_style', 'pdr_button_text_color', 'sanitize_hex_color');
        register_setting('pdr_settings_group_frontend_style', 'pdr_button_hover_color', 'sanitize_hex_color');
        register_setting('pdr_settings_group_frontend_style', 'pdr_button_text_hover_color', 'sanitize_hex_color');
        register_setting('pdr_settings_group_frontend_style', 'pdr_title_font_family');
        register_setting('pdr_settings_group_frontend_style', 'pdr_title_color', 'sanitize_hex_color');
        register_setting('pdr_settings_group_frontend_style', 'pdr_body_font_family');
        register_setting('pdr_settings_group_frontend_style', 'pdr_body_color', 'sanitize_hex_color');
        register_setting('pdr_settings_group_frontend_style', 'pdr_template_choice'); // Novo registro para template

        add_settings_section(
            'pdr_style_settings_section', 
            __('Frontend Style', 'professionaldirectory'), 
            null, 
            'pdr_style_settings'
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
        ];

        foreach ($style_settings as $setting_name => $setting_label) {
            add_settings_field(
                'pdr_' . $setting_name,
                $setting_label,
                array($this, $setting_name . '_callback'),
                'pdr_style_settings', 
                'pdr_style_settings_section'
            );
        }

        // Adicionar o campo de escolha do template
        add_settings_field(
            'pdr_template_choice',
            __('Template Choice', 'professionaldirectory'),
            array($this, 'template_choice_callback'),
            'pdr_style_settings',
            'pdr_style_settings_section'
        );

        // Configurações para a aba "Panel Style"
        register_setting('pdr_settings_group_panel_style', 'pdr_primary_color', 'sanitize_hex_color');
        register_setting('pdr_settings_group_panel_style', 'pdr_secondary_color', 'sanitize_hex_color');
        register_setting('pdr_settings_group_panel_style', 'pdr_text_color', 'sanitize_hex_color');
        register_setting('pdr_settings_group_panel_style', 'pdr_accent_color', 'sanitize_hex_color');
        register_setting('pdr_settings_group_panel_style', 'pdr_panel_logo');

        add_settings_section(
            'pdr_panel_style_section',
            __('Panel Style Settings', 'professionaldirectory'),
            null,
            'pdr_panel_style_settings'
        );

        // Adicionando campos
        add_settings_field(
            'pdr_primary_color',
            __('Primary Color', 'professionaldirectory'),
            array($this, 'primary_color_callback'),
            'pdr_panel_style_settings',
            'pdr_panel_style_section'
        );

        add_settings_field(
            'pdr_secondary_color',
            __('Secondary Color', 'professionaldirectory'),
            array($this, 'secondary_color_callback'),
            'pdr_panel_style_settings',
            'pdr_panel_style_section'
        );

        add_settings_field(
            'pdr_text_color',
            __('Text Color', 'professionaldirectory'),
            array($this, 'text_color_callback'),
            'pdr_panel_style_settings',
            'pdr_panel_style_section'
        );

        add_settings_field(
            'pdr_accent_color',
            __('Accent Color', 'professionaldirectory'),
            array($this, 'accent_color_callback'),
            'pdr_panel_style_settings',
            'pdr_panel_style_section'
        );

        add_settings_field(
            'pdr_panel_logo',
            __('Panel Logo', 'professionaldirectory'),
            array($this, 'panel_logo_callback'),
            'pdr_panel_style_settings',
            'pdr_panel_style_section'
        );
    }

    // Callback General options
    public function general_option_callback() {
        $option_value = get_option('pdr_general_option', ''); 
        echo "<input type='text' name='pdr_general_option' value='" . esc_attr($option_value) . "' />";
    }

    // Callback Delete Data on Uninstall
    public function delete_data_on_uninstall_callback() {
        $option = get_option('pdr_delete_data_on_uninstall', 'no');
        ?>
        <select name="pdr_delete_data_on_uninstall">
            <option value="yes" <?php selected($option, 'yes'); ?>><?php _e('Yes', 'professionaldirectory'); ?></option>
            <option value="no" <?php selected($option, 'no'); ?>><?php _e('No', 'professionaldirectory'); ?></option>
        </select>
        <p class="description"><?php _e('Choose whether to delete all plugin data when the plugin is uninstalled.', 'professionaldirectory'); ?></p>
        <?php
    }

    // Callbacks API Options
    public function google_maps_api_key_callback() {
        $api_key = get_option('pdr_google_maps_api_key');
        echo "<input type='text' name='pdr_google_maps_api_key' value='" . esc_attr($api_key) . "' placeholder='" . esc_attr__('Enter the Google Maps API Key', 'professionaldirectory') . "' />";
    }

    // Callbacks Email Options
    public function selected_admins_callback() {
        $selected_admins = get_option('pdr_selected_admins', []);
        
        if (!is_array($selected_admins)) {
            $selected_admins = explode(',', $selected_admins);
        }
        
        $admins = get_users(['role' => 'administrator']);
    
        echo '<select multiple name="pdr_selected_admins[]" style="width: 100%;">';
        foreach ($admins as $admin) {
            $selected = in_array($admin->user_email, $selected_admins) ? 'selected' : '';
            $admin_display = sprintf('%s (%s)', $admin->display_name, $admin->user_email);
            echo '<option value="' . esc_attr($admin->user_email) . '" ' . $selected . '>' . esc_html($admin_display) . '</option>';
        }
        echo '</select>';
    }
    
    public function manual_emails_callback() {
        $manual_emails = get_option('pdr_manual_emails', '');
        echo "<input type='text' name='pdr_manual_emails' value='" . esc_attr($manual_emails) . "' style='width: 50%;' placeholder='" . esc_attr__('email1@example.com, email2@example.com', 'professionaldirectory') . "' />";
        echo "<p>" . esc_html__('Enter additional emails separated by commas.', 'professionaldirectory') . "</p>";
    }

    // Callbacks Frontend Style options
    public function button_color_callback() {
        $value = get_option('pdr_button_color', '#000000');
        echo "<input type='color' name='pdr_button_color' value='" . esc_attr($value) . "' />";
        echo "<input type='text' name='pdr_button_color_hex' class='color-hex-text-field' value='" . esc_attr($value) . "' placeholder='#ffffff' />";
    }
       
    public function button_text_color_callback() {
        $value = get_option('pdr_button_text_color', '#FFFFFF');
        echo "<input type='color' name='pdr_button_text_color' value='" . esc_attr($value) . "' />";
        echo "<input type='text' name='pdr_button_text_color_hex' class='color-hex-text-field' value='" . esc_attr($value) . "' placeholder='#ffffff' />";
    }

    public function button_hover_color_callback() {
        $value = get_option('pdr_button_hover_color', '#000000');
        echo "<input type='color' name='pdr_button_hover_color' value='" . esc_attr($value) . "' />";
        echo "<input type='text' name='pdr_button_hover_color_hex' class='color-hex-text-field' value='" . esc_attr($value) . "' placeholder='#000000' />";
    }

    public function button_text_hover_color_callback() {
        $value = get_option('pdr_button_text_hover_color', '#FFFFFF');
        echo "<input type='color' name='pdr_button_text_hover_color' value='" . esc_attr($value) . "' />";
        echo "<input type='text' name='pdr_button_text_hover_color_hex' class='color-hex-text-field' value='" . esc_attr($value) . "' placeholder='#ffffff' />";
    }

    public function title_font_family_callback() {
        $value = get_option('pdr_title_font_family', '');
        echo "<input type='text' name='pdr_title_font_family' class='font-text-field' value='" . esc_attr($value) . "' />";
    }

    public function title_color_callback() {
        $value = get_option('pdr_title_color', '#000000');
        echo "<input type='color' name='pdr_title_color' value='" . esc_attr($value) . "' />";
        echo "<input type='text' name='pdr_title_color_hex' class='color-hex-text-field' value='" . esc_attr($value) . "' placeholder='#000000' />";
    }

    public function body_font_family_callback() {
        $value = get_option('pdr_body_font_family', '');
        echo "<input type='text' name='pdr_body_font_family' class='font-text-field' value='" . esc_attr($value) . "' />";
    }

    public function body_color_callback() {
        $value = get_option('pdr_body_color', '#000000');
        echo "<input type='color' name='pdr_body_color' value='" . esc_attr($value) . "' />";
        echo "<input type='text' name='pdr_body_color_hex' class='color-hex-text-field' value='" . esc_attr($value) . "' placeholder='#000000' />";
    }

    public function template_choice_callback() {
        $template_choice = get_option('pdr_template_choice', 'template-1');
        $templates = [
            'template-1' => 'Template 1',
            'template-2' => 'Template 2'
        ];
        ?>
        <div class="template-choice-container">
            <?php foreach ($templates as $template_value => $template_label): ?>
                <label title="<?php echo esc_attr($template_label); ?>">
                    <input type="radio" name="pdr_template_choice" value="<?php echo esc_attr($template_value); ?>" <?php checked($template_choice, $template_value); ?> />
                    <img src="<?php echo plugin_dir_url(PDR_MAIN_FILE) . 'panel/img/' . esc_attr($template_value) . '.jpg'; ?>" alt="<?php echo esc_attr($template_label); ?>" class="template-thumbnail" />
                    <span class="template-label"><?php echo esc_html($template_label); ?></span>
                </label>
            <?php endforeach; ?>
        </div>
        <style>
            .template-choice-container {
                display: flex;
                gap: 20px;
            }
            .template-choice-container label {
                display: block;
                text-align: center;
                cursor: pointer;
                position: relative;
            }
            .template-choice-container input[type="radio"] {
                display: none;
            }
            .template-thumbnail {
                border: 2px solid transparent;
                border-radius: 5px;
                transition: border-color 0.3s;
                width: 150px; /* Ajuste conforme necessário */
                height: auto; /* Mantém a proporção da imagem */
            }
            .template-choice-container input[type="radio"]:checked + .template-thumbnail {
                border-color: #007cba;
            }
            .template-label {
                display: block;
                margin-top: 5px;
                font-size: 14px;
            }
            .template-choice-container label:hover .template-thumbnail {
                border-color: #555;
            }
        </style>
        <?php
    }

    // Callbacks Panel Style
    public function primary_color_callback() {
        $value = get_option('pdr_primary_color', '#0073aa');
        echo "<input type='color' name='pdr_primary_color' value='" . esc_attr($value) . "' />";
        echo "<input type='text' name='pdr_primary_color_hex' class='color-hex-text-field' value='" . esc_attr($value) . "' placeholder='#0073aa' />";
    }

    public function secondary_color_callback() {
        $value = get_option('pdr_secondary_color', '#0073aa');
        echo "<input type='color' name='pdr_secondary_color' value='" . esc_attr($value) . "' />";
        echo "<input type='text' name='pdr_secondary_color_hex' class='color-hex-text-field' value='" . esc_attr($value) . "' placeholder='#0073aa' />";
    }

     public function text_color_callback() {
        $value = get_option('pdr_text_color', '#333333');
        echo "<input type='color' name='pdr_text_color' value='" . esc_attr($value) . "' />";
        echo "<input type='text' name='pdr_text_color_hex' class='color-hex-text-field' value='" . esc_attr($value) . "' placeholder='#333333' />";
    }

    public function accent_color_callback() {
        $value = get_option('pdr_accent_color', '#0073aa');
        echo "<input type='color' name='pdr_accent_color' value='" . esc_attr($value) . "' />";
        echo "<input type='text' name='pdr_accent_color_hex' class='color-hex-text-field' value='" . esc_attr($value) . "' placeholder='#0073aa' />";
    }

    public function panel_logo_callback() {
        $logo_id = get_option('pdr_panel_logo');
        $image_url = wp_get_attachment_url($logo_id);
        ?>
        <input type="hidden" id="pdr_panel_logo" name="pdr_panel_logo" value="<?php echo esc_attr($logo_id); ?>" />
        <input type="button" id="pdr_panel_logo_button" class="button" value="<?php _e('Upload Logo', 'professionaldirectory'); ?>" />
        <input type="button" id="pdr_panel_logo_remove_button" class="button" value="<?php _e('Remove Logo', 'professionaldirectory'); ?>" <?php echo $logo_id ? '' : 'style="display:none;"'; ?> />
        <span class="description"><?php _e('Upload or remove the panel logo.', 'professionaldirectory'); ?></span>
        <div id="pdr_panel_logo_preview" style="min-height: 100px;">
            <?php if($image_url): ?>
                <img style="max-width:250px;" src="<?php echo esc_url($image_url); ?>" />
            <?php endif; ?>
        </div>
        <script>
        jQuery(document).ready(function($){
            $('#pdr_panel_logo_button').click(function(e) {
                e.preventDefault();
                var image_frame;
                if(image_frame){
                    image_frame.open();
                }
                image_frame = wp.media({
                    title: 'Select Media',
                    multiple : false,
                    library : {
                        type : 'image',
                    }
                });
                
                image_frame.on('close',function() {
                    var selection =  image_frame.state().get('selection').first().toJSON();
                    $('#pdr_panel_logo').val(selection.id);
                    $('#pdr_panel_logo_preview').html('<img src="'+selection.sizes.full.url+'" style="max-width:100%;"/>');
                    $('#pdr_panel_logo_remove_button').show();
                });
                
                image_frame.on('open',function() {
                    var selection =  image_frame.state().get('selection');
                    var ids = $('#pdr_panel_logo').val().split(',');
                    ids.forEach(function(id) {
                        var attachment = wp.media.attachment(id);
                        attachment.fetch();
                        selection.add( attachment ? [ attachment ] : [] );
                    });
                
                });
                
                image_frame.open();
            });

            $('#pdr_panel_logo_remove_button').click(function(e){
                e.preventDefault();
                $('#pdr_panel_logo').val('');
                $('#pdr_panel_logo_preview').html('');
                $(this).hide();
            });

            if($('#pdr_panel_logo').val()) {
                $('#pdr_panel_logo_remove_button').show();
            }
        });
        </script>
        <?php
    }
}
?>

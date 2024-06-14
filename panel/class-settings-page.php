<?php
defined('ABSPATH') or die('No script kiddies please!');

class RHB_Settings {
    private $settings;

    public function __construct() {
        $this->settings = $this->get_settings();
        add_action('admin_init', array($this, 'register_settings'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_assets'));
    }

    public function register_settings() {
        // Registrar um único grupo de configurações
        register_setting('rhb_settings_group', 'rhb_settings');
    
        foreach ($this->settings as $section_id => $section) {
            add_settings_section(
                $section_id,
                $section['title'],
                '__return_false',
                'rhb_settings_' . $section_id
            );
    
            foreach ($section['fields'] as $field_id => $field) {
                add_settings_field(
                    $field_id,
                    $field['label'],
                    array($this, 'render_field'),
                    'rhb_settings_' . $section_id,
                    $section_id,
                    array('id' => $field_id, 'field' => $field)
                );
            }
        }
    }


    public function selected_admins_callback() {
        $options = get_option('rhb_settings');
        $selected_admins = $options['rhb_selected_admins'] ?? [];
        $admins = get_users(['role' => 'administrator']);
    
        echo '<select multiple name="rhb_settings[rhb_selected_admins][]" style="width: 100%;">';
        foreach ($admins as $admin) {
            $selected = in_array($admin->user_email, $selected_admins) ? 'selected' : '';
            $admin_display = sprintf('%s (%s)', $admin->display_name, $admin->user_email);
            echo '<option value="' . esc_attr($admin->user_email) . '" ' . $selected . '>' . esc_html($admin_display) . '</option>';
        }
        echo '</select>';
    }
    


    public function render_settings_page() {
        ?>
        <div class="wrap">
            <h1><?php _e('ReferralHub Settings', 'referralhub'); ?></h1>
            <div class="rhb-settings">
                <div class="rhb-top-bar">
                    <button type="button" class="button rhb-save-button"><?php _e('Save Changes', 'referralhub'); ?></button>
                </div>
                <div class="rhb-settings-container">
                    <div class="rhb-settings-sidebar">
                        <ul>
                            <li><a href="#general_settings" class="rhb-settings-tab"><i class="fas fa-cog"></i> <?php _e('General Settings', 'referralhub'); ?></a></li>
                            <li><a href="#api_settings" class="rhb-settings-tab"><i class="fas fa-code"></i> <?php _e('API Settings', 'referralhub'); ?></a></li>
                            <li><a href="#email_settings" class="rhb-settings-tab"><i class="fas fa-envelope"></i> <?php _e('Email Settings', 'referralhub'); ?></a></li>
                            <li><a href="#style_settings" class="rhb-settings-tab"><i class="fas fa-paint-brush"></i> <?php _e('Frontend Style', 'referralhub'); ?></a></li>
                            <li><a href="#panel_style" class="rhb-settings-tab"><i class="fas fa-tachometer-alt"></i> <?php _e('Panel Style', 'referralhub'); ?></a></li>
                            <li><a href="#referral_fee_settings" class="rhb-settings-tab"><i class="fas fa-hand-holding-usd"></i> <?php _e('Referral Fees', 'referralhub'); ?></a></li>
                            <li><a href="#advanced_settings" class="rhb-settings-tab"><i class="fas fa-tools"></i> <?php _e('Advanced', 'referralhub'); ?></a></li>
                        </ul>
                    </div>
                    <div class="rhb-settings-content">
                        <form method="post" action="options.php">
                            <?php
                            // Chamar settings_fields apenas uma vez
                            settings_fields('rhb_settings_group');
    
                            foreach ($this->settings as $section_id => $section) {
                                echo "<div id='".esc_attr($section_id)."' class='rhb-settings-section-content'>";
                                do_settings_sections('rhb_settings_' . $section_id);
                                echo "</div>";
                            }
                            submit_button();
                            ?>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }

    public function render_field($args) {
        $field = $args['field'];
        $id = $args['id'];
        $options = get_option('rhb_settings');
        $value = isset($options[$id]) ? $options[$id] : $field['default'];
        $attributes = $field['attributes'] ?? [];
    
        switch ($field['type']) {
            case 'text':
                echo "<input type='text' id='$id' name='rhb_settings[$id]' value='$value' class='regular-text' />";
                break;
                case 'number':
                    // Certifique-se de formatar o valor para a visualização correta
                    $formattedValue = number_format((float)$value, 2, ',', '.');
                    echo "<input type='text' id='$id' name='rhb_settings[$id]' value='$formattedValue' class='regular-text rhb-number-field' />";
                    break;
            case 'checkbox':
                $checked = $value ? 'checked' : '';
                echo "<input type='checkbox' id='$id' name='rhb_settings[$id]' value='1' $checked />";
                break;
            case 'color':
                echo "<input type='color' id='$id' name='rhb_settings[$id]' value='$value' />";
                echo "<input type='text' id='{$id}_hex' name='rhb_settings[{$id}_hex]' class='color-hex-text-field' value='$value' placeholder='#ffffff' />";
                break;
            case 'select':
                echo "<select id='$id' name='rhb_settings[$id]'>";
                foreach ($field['options'] as $option_value => $option_label) {
                    $selected = $value == $option_value ? 'selected' : '';
                    echo "<option value='$option_value' $selected>$option_label</option>";
                }
                echo "</select>";
                break;
            case 'radio':
                foreach ($field['options'] as $option_value => $option_label) {
                    $checked = $value == $option_value ? 'checked' : '';
                    echo "<label><input type='radio' name='rhb_settings[$id]' value='$option_value' $checked /> $option_label</label><br />";
                }
                break;
            case 'media':
                $image_url = $value ? wp_get_attachment_url($value) : '';
                echo "<input type='hidden' id='$id' name='rhb_settings[$id]' value='$value' />";
                echo "<img id='{$id}_preview' src='$image_url' style='max-width:150px;' />";
                echo "<button type='button' class='button' id='{$id}_button'>" . __('Upload Logo', 'referralhub') . "</button>";
                echo "<button type='button' class='button' id='{$id}_remove'>" . __('Remove Logo', 'referralhub') . "</button>";
                break;
            case 'font':
                $google_fonts = $this->get_google_fonts();
                echo "<select id='$id' name='rhb_settings[$id]'>";
                foreach ($google_fonts as $font) {
                    $selected = $value == $font ? 'selected' : '';
                    echo "<option value='$font' $selected>$font</option>";
                }
                echo "</select>";
                break;
            case 'custom':
                if (is_callable($field['callback'])) {
                    call_user_func($field['callback']);
                }
                break;
            // Adicione outros tipos de campos conforme necessário
        }
    }
    
    private function get_google_fonts() {
        // Esta lista pode ser gerada dinamicamente ou mantida manualmente
        return array(
            'Roboto',
            'Open Sans',
            'Lato',
            'Montserrat',
            'Oswald',
            'Raleway',
            'Merriweather',
            'Ubuntu',
            'PT Sans',
            'Noto Sans',
            'Nunito',
            'Roboto Condensed',
            'Source Sans Pro',
            'Poppins',
            'Playfair Display',
        );
    }
    

    public function template_choice_callback() {
        $template_choice = get_option('rhb_settings')['rhb_template_choice'] ?? 'template-1';
        $templates = [
            'template-1' => 'Template 1',
            'template-2' => 'Template 2'
        ];
        ?>
        <div class="template-choice-container">
            <?php foreach ($templates as $template_value => $template_label): ?>
                <label title="<?php echo esc_attr($template_label); ?>">
                    <input type="radio" name="rhb_settings[rhb_template_choice]" value="<?php echo esc_attr($template_value); ?>" <?php checked($template_choice, $template_value); ?> />
                    <img src="<?php echo plugin_dir_url(__FILE__) . 'img/' . esc_attr($template_value) . '.jpg'; ?>" alt="<?php echo esc_attr($template_label); ?>" class="template-thumbnail" />
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

    private function get_settings() {
        return array(
            'general_settings' => array(
                'title' => __('General Settings', 'referralhub'),
                'fields' => array(
                    'rhb_general_option' => array(
                        'label' => __('General Option', 'referralhub'),
                        'type' => 'text',
                        'default' => ''
                    ),
                    'rhb_delete_data_on_uninstall' => array(
                        'label' => __('Delete Data on Uninstall', 'referralhub'),
                        'type' => 'checkbox',
                        'default' => ''
                    )
                )
            ),
            'api_settings' => array(
                'title' => __('API Settings', 'referralhub'),
                'fields' => array(
                    'rhb_google_maps_api_key' => array(
                        'label' => __('Google Maps API Key', 'referralhub'),
                        'type' => 'text',
                        'default' => ''
                    )
                )
            ),
            'email_settings' => array(
              'title' => __('Email Settings', 'referralhub'),
                 'fields' => array(
             'rhb_selected_admins' => array(
            'label' => __('Admins to Receive Emails', 'referralhub'),
            'type' => 'custom', // Mude o tipo para custom para usar o callback personalizado
            'callback' => array($this, 'selected_admins_callback'),
            'default' => []
             ),
            'rhb_manual_emails' => array(
            'label' => __('Additional Emails', 'referralhub'),
            'type' => 'text',
            'default' => ''
            )
        )
        ),
            'style_settings' => array(
                'title' => __('Frontend Style', 'referralhub'),
                'fields' => array(
                    'rhb_button_color' => array(
                        'label' => __('Button Color', 'referralhub'),
                        'type' => 'color',
                        'default' => '#000000'
                    ),
                    'rhb_button_text_color' => array(
                        'label' => __('Button Text Color', 'referralhub'),
                        'type' => 'color',
                        'default' => '#FFFFFF'
                    ),
                    'rhb_button_hover_color' => array(
                        'label' => __('Button Hover Color', 'referralhub'),
                        'type' => 'color',
                        'default' => '#000000'
                    ),
                    'rhb_button_text_hover_color' => array(
                        'label' => __('Button Text Hover Color', 'referralhub'),
                        'type' => 'color',
                        'default' => '#FFFFFF'
                    ),
                    'rhb_title_font_family' => array(
                        'label' => __('Title Font Family', 'referralhub'),
                        'type' => 'font', // Atualizado para usar o novo tipo de campo de fonte
                        'default' => ''
                    ),
                    'rhb_title_color' => array(
                        'label' => __('Title Color', 'referralhub'),
                        'type' => 'color',
                        'default' => '#000000'
                    ),
                    'rhb_body_font_family' => array(
                        'label' => __('Body Font Family', 'referralhub'),
                        'type' => 'font', // Atualizado para usar o novo tipo de campo de fonte
                        'default' => ''
                    ),
                    'rhb_body_color' => array(
                        'label' => __('Body Color', 'referralhub'),
                        'type' => 'color',
                        'default' => '#000000'
                    ),
                    'rhb_template_choice' => array(
                        'label' => __('Template Choice', 'referralhub'),
                        'type' => 'custom',
                        'callback' => array($this, 'template_choice_callback'),
                        'default' => 'template-1',
                    )
                )
            ),
            'panel_style' => array(
                'title' => __('Panel Style', 'referralhub'),
                'fields' => array(
                    'rhb_primary_color' => array(
                        'label' => __('Primary Color', 'referralhub'),
                        'type' => 'color',
                        'default' => '#0073aa'
                    ),
                    'rhb_secondary_color' => array(
                        'label' => __('Secondary Color', 'referralhub'),
                        'type' => 'color',
                        'default' => '#0073aa'
                    ),
                    'rhb_text_color' => array(
                        'label' => __('Text Color', 'referralhub'),
                        'type' => 'color',
                        'default' => '#333333'
                    ),
                    'rhb_accent_color' => array(
                        'label' => __('Accent Color', 'referralhub'),
                        'type' => 'color',
                        'default' => '#0073aa'
                    ),
                    'rhb_panel_logo' => array(
                        'label' => __('Panel Logo', 'referralhub'),
                        'type' => 'media',
                        'default' => ''
                    )
                )
            ),
            'referral_fee_settings' => array(
    'title' => __('Referral Fee Settings', 'referralhub'),
    'fields' => array(
        'rhb_referral_fee_type' => array(
            'label' => __('Referral Fee Type', 'referralhub'),
            'type' => 'select',
            'options' => array(
                'view' => __('Per View', 'referralhub'),
                'agreement_reached' => __('Per Agreement Reached', 'referralhub'),
                'both' => __('Combination of Both', 'referralhub')
            ),
            'default' => 'view'
        ),
        'rhb_general_referral_fee_view' => array(
            'label' => __('Per View', 'referralhub'),
            'type' => 'number',
            'default' => '0.00',
            'attributes' => array(
                'step' => '0.01',  // Permite valores decimais até duas casas
                'min' => '0'       // Valor mínimo permitido
            )
        ),
        'rhb_general_referral_fee_agreement_reached' => array(
            'label' => __('Per Agreement Reached', 'referralhub'),
            'type' => 'number',
            'default' => '0.00',
            'attributes' => array(
                'step' => '0.01',  // Permite valores decimais até duas casas
                'min' => '0'       // Valor mínimo permitido
            )
        )
    )
),
            'advanced_settings' => array(
                'title' => __('Advanced', 'referralhub'),
                'fields' => array(
                    'rhb_export_data' => array(
                        'label' => __('Export Data', 'referralhub'),
                        'type' => 'text', // Implementar exportação de dados
                        'default' => ''
                    )
                )
            )
        );
    }

    public function enqueue_assets($hook) {
        if ($hook != 'rhb_service_page_rhb-general-settings') {
            return;
        }
        wp_enqueue_script('rhb-settings-page-colors', plugin_dir_url(__FILE__) . 'js/settings-page-colors.js', array('jquery'), '1.0.0', true);
        wp_enqueue_style('rhb-admin-css', plugin_dir_url(__FILE__) . 'css/admin-main.css', array(), '1.0.0');
        wp_enqueue_script('rhb-admin-js', plugin_dir_url(__FILE__) . 'js/admin-settings-manager.js', array('jquery'), '1.0.0', true);
        wp_enqueue_style('font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css', array(), '5.15.4');
        wp_enqueue_media();
        wp_enqueue_script('google-fonts-api', 'https://fonts.googleapis.com/css2?family=Roboto&display=swap', array(), null, true);
    }
}

new RHB_Settings();
?>

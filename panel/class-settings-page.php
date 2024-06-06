<?php
defined('ABSPATH') or die('No script kiddies please!');

class RHB_Settings {
    private $settings;

    public function __construct() {
        $this->settings = $this->get_settings();
        add_action('admin_menu', array($this, 'add_menu_page'));
        add_action('admin_init', array($this, 'register_settings'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_assets'));
    }

    public function add_menu_page() {
        add_menu_page(
            __('ReferralHub Settings', 'referralhub'),
            __('Settings', 'referralhub'),
            'manage_options',
            'rhb-settings',
            array($this, 'render_settings_page'),
            'dashicons-admin-generic'
        );
    }

    public function register_settings() {
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
                register_setting('rhb_settings_' . $section_id, $field_id);
            }
        }
    }

    public function render_settings_page() {
        ?>
        <div class="wrap">
            <h1><?php _e('ReferralHub Settings', 'referralhub'); ?></h1>
            <div class="rhb-settings">
                <div class="rhb-settings-container">
                    <div class="rhb-settings-sidebar">
                        <ul>
                            <?php foreach ($this->settings as $section_id => $section): ?>
                                <li>
                                    <a href="#<?php echo esc_attr($section_id); ?>" class="rhb-settings-tab">
                                        <?php echo esc_html($section['title']); ?>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                    <div class="rhb-settings-content">
                        <form method="post" action="options.php">
                            <?php
                            settings_fields('rhb_settings_' . $section_id);
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
        $value = get_option($id, $field['default']);
        
        switch ($field['type']) {
            case 'text':
                echo "<input type='text' id='$id' name='$id' value='$value' class='regular-text' />";
                break;
            case 'checkbox':
                $checked = $value ? 'checked' : '';
                echo "<input type='checkbox' id='$id' name='$id' value='1' $checked />";
                break;
            case 'color':
                echo "<input type='color' id='$id' name='$id' value='$value' />";
                echo "<input type='text' id='{$id}_hex' name='{$id}_hex' class='color-hex-text-field' value='$value' placeholder='#ffffff' />";
                break;
            case 'radio':
                foreach ($field['options'] as $option_value => $option_label) {
                    $checked = $value == $option_value ? 'checked' : '';
                    echo "<label><input type='radio' name='$id' value='$option_value' $checked /> $option_label</label><br />";
                }
                break;
            // Adicione outros tipos de campos conforme necessário
        }
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
                        'type' => 'text', // Ajustar para múltipla seleção com jQuery
                        'default' => ''
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
                        'type' => 'text',
                        'default' => ''
                    ),
                    'rhb_title_color' => array(
                        'label' => __('Title Color', 'referralhub'),
                        'type' => 'color',
                        'default' => '#000000'
                    ),
                    'rhb_body_font_family' => array(
                        'label' => __('Body Font Family', 'referralhub'),
                        'type' => 'text',
                        'default' => ''
                    ),
                    'rhb_body_color' => array(
                        'label' => __('Body Color', 'referralhub'),
                        'type' => 'color',
                        'default' => '#000000'
                    ),
                    'rhb_template_choice' => array(
                        'label' => __('Template Choice', 'referralhub'),
                        'type' => 'radio',
                        'default' => 'template-1',
                        'options' => array(
                            'template-1' => 'Template 1',
                            'template-2' => 'Template 2'
                        )
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
                        'type' => 'text', // Implementar upload de imagem via Media Library
                        'default' => ''
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
        wp_enqueue_style('rhb-admin-css', plugin_dir_url(__FILE__) . 'css/admin-main.css', array(), '1.0.0');
        wp_enqueue_script('rhb-admin-js', plugin_dir_url(__FILE__) . 'js/admin-settings-manager.js', array('jquery'), '1.0.0', true);
    }
}

new RHB_Settings();
?>







    


<?php
class MyPlugin_Admin {

    public function __construct() {
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('admin_init', array($this, 'register_settings'));
    }

    public function add_admin_menu() {
        add_menu_page(
            'Configurações do Meu Plugin',
            'Meu Plugin',
            'manage_options',
            'myplugin',
            array($this, 'settings_page'),
            'dashicons-admin-generic',
            6
        );
    }

    public function settings_page() {
        ?>
        <div class="wrap">
            <h2>Configurações do Meu Plugin</h2>
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
    }
    
    public function google_maps_api_key_callback() {
        $api_key = get_option('myplugin_google_maps_api_key');
        echo "<input type='text' name='myplugin_google_maps_api_key' value='" . esc_attr($api_key) . "' />";
    }
    
}

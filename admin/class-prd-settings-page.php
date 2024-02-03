<?php
// Verifica se o WordPress está carregando este arquivo diretamente.
if (!defined('WPINC')) {
    die;
}

class PDR_Settings {

    public function __construct() {
        add_action('admin_init', array($this, 'register_settings'));
    }

    public function register_settings() {
        // Registro das configurações, seções e campos
        register_setting('prd_plugin_settings_group', 'myplugin_api_key');
        
        add_settings_section(
            'myplugin_api_settings',
            __('API Settings', 'professionaldirectory'),
            null,
            'prd_plugin_settings'
        );
        
        add_settings_field(
            'myplugin_api_key',
            __('API Key', 'professionaldirectory'),
            array($this, 'api_key_callback'),
            'prd_plugin_settings',
            'myplugin_api_settings'
        );

        // Adicione mais registros conforme necessário
    }

    public function api_key_callback() {
        $api_key = get_option('myplugin_api_key');
        echo "<input type='text' name='myplugin_api_key' value='" . esc_attr($api_key) . "' />";
    }

    // Callbacks adicionais aqui

    private function get_settings_tabs() {
        return array(
            'api_settings' => __('API Settings', 'professionaldirectory'),
            'email_settings' => __('Email Settings', 'professionaldirectory'),
            'frontend_style_settings' => __('Frontend Style Settings', 'professionaldirectory'),
        );
    }

    public function plugin_settings_page_render() {
        $current_tab = isset($_GET['tab']) ? $_GET['tab'] : 'api_settings';
        $tabs = $this->get_settings_tabs();
        $post_type = isset($_GET['post_type']) ? $_GET['post_type'] : 'default_post_type'; // Adicione um valor padrão ou trate conforme necessário
    
        echo '<div class="wrap"><h1>' . __('Configurações do Plugin', 'professionaldirectory') . '</h1><h2 class="nav-tab-wrapper">';
        foreach ($tabs as $tab => $label) {
            $active = ($tab === $current_tab) ? 'nav-tab-active' : '';
            echo "<a class='nav-tab " . $active . "' href='?post_type=" . esc_attr($post_type) . "&page=prd_plugin_settings&tab=" . $tab . "'>" . $label . "</a>";
        }
        echo '</h2>';
        $this->plugin_settings_page_content($current_tab);
        echo '</div>';
    }
    

    public function plugin_settings_page_content($current_tab) {
        // Ajuste o caminho base para apontar para o diretório 'settings-tabs'
        $path = plugin_dir_path(__FILE__) . 'settings-tabs' . DIRECTORY_SEPARATOR;
    
        switch ($current_tab) {
            case 'api_settings':
                $file = $path . 'api-settings.php';
                break;
            case 'email_settings':
                $file = $path . 'email-settings.php';
                break;
            case 'frontend_style_settings':
                $file = $path . 'frontend-style-settings.php';
                break;
            default:
                echo '<p>' . __('Erro: Aba não encontrada.', 'professionaldirectory') . '</p>';
                return;
        }
    
        if (file_exists($file)) {
            include $file;
        } else {
            echo '<p>' . sprintf(__('Erro: O arquivo %s não foi encontrado.', 'professionaldirectory'), $file) . '</p>';
        }
    }
    
}
// A instanciação da classe é mantida para garantir que as configurações sejam registradas.
new PDR_Settings();

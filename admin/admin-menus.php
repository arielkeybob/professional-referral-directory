<?php
// Se este arquivo for chamado diretamente, aborte.
if (!defined('WPINC')) {
    die;
}

// Adiciona capacidade específica ao papel 'professional'
function pdr_add_dashboard_capability() {
    $role = get_role('professional'); // Substitua 'professional' pelo papel exato
    if ($role) {
        $role->add_cap('view_pdr_dashboard'); // Adiciona uma nova capacidade
    }
}
add_action('init', 'pdr_add_dashboard_capability');

// Adiciona submenu 'Dashboard' ao menu do tipo de post 'professional_service'
function pdr_add_dashboard_submenu() {
    add_submenu_page(
        'edit.php?post_type=professional_service',
        'Dashboard do Professional',
        'Dashboard',
        'view_pdr_dashboard', // Usa a nova capacidade aqui
        'pdr-dashboard',
        'pdr_dashboard_page_content'
    );
}
add_action('admin_menu', 'pdr_add_dashboard_submenu');

function pdr_dashboard_page_content() {
    include plugin_dir_path(__FILE__) . 'templates/dashboard-template-professional.php';
}

function add_custom_submenu_page() {
    add_submenu_page(
        'edit.php?post_type=professional_service',
        'Dashboard do Admin',
        'Dashboard do Admin',
        'manage_options',
        'dashboard-admin',
        'dashboard_admin_page_callback'
    );
}
add_action('admin_menu', 'add_custom_submenu_page');

function dashboard_admin_page_callback() {
    include plugin_dir_path(__FILE__) . 'templates/dashboard-template-admin.php';
}

function pdr_add_shortcodes_help_page() {
    add_submenu_page(
        'edit.php?post_type=professional_service',
        'Ajuda de Shortcodes',
        'Ajuda de Shortcodes',
        'manage_options',
        'pdr-shortcodes-help',
        'pdr_render_shortcodes_help_page'
    );
}
add_action('admin_menu', 'pdr_add_shortcodes_help_page');

function pdr_render_shortcodes_help_page() {
    include plugin_dir_path(__FILE__) . 'shortcodes-help-page.php';
}

// Corrigindo a adição da página de configurações como um submenu de 'Services'
function pdr_add_plugin_settings_page() {
    add_submenu_page(
        'edit.php?post_type=professional_service', // Adiciona como um submenu de 'Services'
        __('Configurações do Plugin', 'professionaldirectory'),
        __('Configurações do Plugin', 'professionaldirectory'),
        'manage_options',
        'prd_plugin_settings',
        'prd_settings_page_render'
    );
}
add_action('admin_menu', 'pdr_add_plugin_settings_page');

function prd_settings_page_render() {
    require_once plugin_dir_path(__FILE__) . 'class-prd-settings-page.php';
    $settings_page = new PDR_Settings();
    $settings_page->plugin_settings_page_render(isset($_GET['tab']) ? $_GET['tab'] : 'api_settings');
}



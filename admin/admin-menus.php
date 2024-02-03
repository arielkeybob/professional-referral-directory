<?php
// Se este arquivo for chamado diretamente, aborte.
if (!defined('WPINC')) {
    die;
}

// Adiciona o submenu dashboard ao menu do post type Services
function pdr_add_dashboard_capability() {
    $role = get_role('professional');
    if ($role) {
        $role->add_cap('view_pdr_dashboard');
    }
}
add_action('init', 'pdr_add_dashboard_capability');

function pdr_add_dashboard_submenu() {
    add_submenu_page(
        'edit.php?post_type=professional_service',
        'Dashboard do Professional',
        'Dashboard',
        'view_pdr_dashboard',
        'pdr-dashboard',
        'pdr_dashboard_page_content'
    );
}
add_action('admin_menu', 'pdr_add_dashboard_submenu');

function pdr_dashboard_page_content() {
    include 'templates/dashboard-template-professional.php';
}

add_action('admin_menu', 'add_custom_submenu_page');

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

function dashboard_admin_page_callback() {
    include 'templates/dashboard-template-admin.php';
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
    include plugin_dir_path(__FILE__) . '/shortcodes-help-page.php';
}

// Adicionando a página de configurações gerais ao submenu
function pdr_add_settings_submenu() {
    add_submenu_page(
        'edit.php?post_type=professional_service',
        __('General Settings', 'professionaldirectory'),
        __('Settings', 'professionaldirectory'),
        'manage_options',
        'myplugin',
        'pdr_settings_page'
    );
}
add_action('admin_menu', 'pdr_add_settings_submenu');

// Inclui o arquivo da classe das configurações para renderizar a página
function pdr_settings_page() {
    require_once plugin_dir_path(__FILE__) . 'class-prd-settings-page.php';
    $settings_page = new PDR_Settings();
    $settings_page->settings_page();
}

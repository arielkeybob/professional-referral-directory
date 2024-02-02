<?php
// Se este arquivo for chamado diretamente, aborte.
if (!defined('WPINC')) {
    die;
}

// Adiciona o submenu dashboard ao menu do post type Services
function pdr_add_dashboard_capability() {
    $role = get_role('professional'); // Substitua 'professional' pelo papel exato
    if ($role) {
        $role->add_cap('view_pdr_dashboard'); // Adicione uma nova capacidade
    }
}
add_action('init', 'pdr_add_dashboard_capability');

// Atualize a função pdr_add_dashboard_submenu para usar a nova capacidade
function pdr_add_dashboard_submenu() {
    add_submenu_page(
        'edit.php?post_type=professional_service',
        'Dashboard do Professional',
        'Dashboard',
        'view_pdr_dashboard',  // Use a nova capacidade aqui
        'pdr-dashboard',
        'pdr_dashboard_page_content'
    );
}
add_action('admin_menu', 'pdr_add_dashboard_submenu');

function pdr_dashboard_page_content() {
    // Corrige o caminho para referenciar o diretório 'templates'
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
    // Corrige o caminho para referenciar o diretório 'templates'
    include 'templates/dashboard-template-admin.php';
}

// Função para adicionar a página de ajuda dos shortcodes
function pdr_add_shortcodes_help_page() {
    add_submenu_page(
        'edit.php?post_type=professional_service',
        'Ajuda de Shortcodes',
        'Ajuda de Shortcodes',
        'manage_options',
        'pdr-shortcodes-help',
        'pdr_render_shortcodes_help_page' // Mantém esta função de callback
    );
}
add_action('admin_menu', 'pdr_add_shortcodes_help_page');

// Atualiza esta função para incluir o arquivo da página de ajuda dos shortcodes
function pdr_render_shortcodes_help_page() {
    include plugin_dir_path(__FILE__) . '/shortcodes-help-page.php';
}
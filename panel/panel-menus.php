<?php
// Verificação para garantir que o arquivo não seja acessado diretamente.
if (!defined('WPINC')) {
    die;
}





// Adiciona capacidades ao papel 'professional' e registra menus e submenus.
function pdr_initialize_panel_menus() {
    add_action('admin_menu', 'pdr_register_menus');
    add_action('init', 'pdr_add_roles_and_capabilities');
    add_action('admin_menu', 'pdr_remove_default_dashboard_for_professionals', 999);
}

// Adiciona as capacidades necessárias ao papel 'professional'.
function pdr_add_roles_and_capabilities() {
    $role = get_role('professional');

    // Verifica se o papel existe antes de tentar adicionar capacidades.
    if ($role) {
        // Adiciona a capacidade de ver o dashboard do professional e os contatos.
        $role->add_cap('view_pdr_dashboard');
        $role->add_cap('view_pdr_contacts');
        // Adicione outras capacidades conforme necessário aqui.
    }
}

// Registra menus e submenus no painel de administração.
function pdr_register_menus() {

    // Adiciona um menu para o Dashboard do Professional
    add_menu_page(
        __('Dashboard do Professional', 'professionaldirectory'),
        __('Dashboard Professional', 'professionaldirectory'),
        'view_pdr_dashboard',
        'pdr-professional-dashboard',
        'pdr_dashboard_page_content',
        'dashicons-businessman',
        3
    );

    // Menu de Gerenciamento de Contatos como um menu principal.
    add_menu_page(
        __('Gerenciamento de Contatos', 'professionaldirectory'),
        __('Contatos', 'professionaldirectory'),
        'view_pdr_contacts',
        'pdr-contacts',
        'pdr_contacts_page_content',
        'dashicons-businessman',
        6
    );

    
    add_submenu_page(
        'edit.php?post_type=professional_service',
        __('Dashboard do Admin', 'professionaldirectory'),
        __('Dashboard do Admin', 'professionaldirectory'),
        'manage_options',
        'dashboard-admin',
        'pdr_dashboard_admin_page_content'
    );
    

    // Submenu de Ajuda de Shortcodes.
    add_submenu_page(
        'edit.php?post_type=professional_service',
        __('Ajuda de Shortcodes', 'professionaldirectory'),
        __('Ajuda de Shortcodes', 'professionaldirectory'),
        'manage_options',
        'pdr-shortcodes-help',
        'pdr_render_shortcodes_help_page'
    );

    // Submenu de Configurações Gerais.
    add_submenu_page(
        'edit.php?post_type=professional_service',
        __('Configurações Gerais', 'professionaldirectory'),
        __('Configurações', 'professionaldirectory'),
        'manage_options',
        'pdr-general-settings',
        'pdr_settings_page'
    );

    // Registra a página de detalhes do contato como uma página 'fantasma'
    add_submenu_page(
        null, // Não exibe no menu
        __('Detalhes do Contato', 'professionaldirectory'),
        null, // Não exibe no menu
        'view_pdr_contacts',
        'pdr-contact-details',
        'pdr_contact_details_page_content'
    );
}


function pdr_dashboard_admin_page_content() {
    include plugin_dir_path(__FILE__) . 'templates/dashboard-template-admin.php';
}


function pdr_dashboard_page_content() {
    // Inclui o arquivo que contém o conteúdo do dashboard do professional
    include plugin_dir_path(__FILE__) . 'templates/dashboard-template-professional.php';
}

function pdr_remove_default_dashboard_for_professionals() {
    // Verifica se o usuário atual tem o papel de 'professional'
    if (current_user_can('professional')) {
        // Remove o Dashboard padrão
        remove_menu_page('index.php');
    }
}

// Função que renderiza o conteúdo da página de Contatos.
function pdr_contacts_page_content() {
    require_once plugin_dir_path(__FILE__) . 'class-contacts-admin.php';
    $contactsPage = new Contatos_Admin_Page();
    $contactsPage->render();
}

// Função para renderizar o conteúdo da página de detalhes do contato
function pdr_contact_details_page_content() {
    require_once plugin_dir_path(__FILE__) . 'single-contact.php';
}

// Função para a página de ajuda de shortcodes.
function pdr_render_shortcodes_help_page() {
    include plugin_dir_path(__FILE__) . 'templates/shortcodes-help-page.php';
}

// Função para a página de configurações gerais.
function pdr_settings_page() {
    include plugin_dir_path(__FILE__) . 'templates/settings-page.php';
}

// Inicialização
pdr_initialize_panel_menus();

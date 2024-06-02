<?php
defined('ABSPATH') or die('No script kiddies please!');

require_once plugin_dir_path(__FILE__) . 'class-settings-page.php';
require_once plugin_dir_path(__FILE__) . 'commission-settings.php'; // Certifique-se de incluir o arquivo onde a função está definida

// Adiciona capacidades ao papel 'professional' e registra menus e submenus.
function pdr_initialize_panel_menus() {
    add_action('admin_menu', 'pdr_register_menus');
    add_action('init', 'pdr_add_roles_and_capabilities');
    add_action('admin_menu', 'pdr_remove_default_dashboard_for_professionals', 999);
    add_action('admin_init', 'pdr_handle_create_pages'); // Adiciona o handler para criar páginas
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
    // Instancia a classe PDR_Settings aqui para uso nos callbacks.
    $pdr_plugin_settings = new PDR_Settings();

    // Submenu de Boas-vindas.
    add_submenu_page(
        'edit.php?post_type=professional_service',
        __('Boas-vindas', 'professionaldirectory'),
        __('Boas-vindas', 'professionaldirectory'),
        'manage_options',
        'pdr-welcome-page',
        'pdr_welcome_page_content'
    );

    // Submenu de Comissões.
    add_submenu_page(
        'edit.php?post_type=professional_service',
        __('Comissões', 'professionaldirectory'),
        __('Comissões', 'professionaldirectory'),
        'manage_options',
        'pdr-commissions-page',
        'pdr_commissions_settings_page' // Esta é a função correta a ser chamada
    );

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

    // Submenu de Configurações Gerais utilizando a instância de PDR_Settings.
    add_submenu_page(
        'edit.php?post_type=professional_service',
        __('Configurações Gerais', 'professionaldirectory'),
        __('Configurações', 'professionaldirectory'),
        'manage_options',
        'pdr-general-settings',
        [$pdr_plugin_settings, 'settings_page']
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
    include plugin_dir_path(__FILE__) . '/shortcodes-help-page.php';
}

// Função para a página de boas-vindas.
function pdr_welcome_page_content() {
    include plugin_dir_path(__FILE__) . '/welcome-page.php';
}

// Função para lidar com a criação de páginas.
function pdr_handle_create_pages() {
    $inquiry_page_id = get_option('pdr_inquiry_page_id');
    $page_exists = $inquiry_page_id && get_post_status($inquiry_page_id);

    if (isset($_POST['pdr_create_pages_submit']) && check_admin_referer('pdr_create_pages', 'pdr_create_pages_nonce')) {
        if (isset($_POST['create_inquiry_page']) && !$page_exists) {
            // Cria a página de Inquiry de serviços
            $page_id = wp_insert_post([
                'post_title' => __('Inquiry de Serviços', 'professional-directory'),
                'post_content' => '[pdr_inquiry_form][pdr_inquiry_results]',
                'post_status' => 'publish',
                'post_type' => 'page'
            ]);
            if ($page_id) {
                update_option('pdr_inquiry_page_id', $page_id);
                wp_redirect(admin_url('edit.php?post_type=professional_service&page=pdr-welcome-page&created=true'));
                exit;
            }
        }
    }
}

// Inicialização
pdr_initialize_panel_menus();
?>

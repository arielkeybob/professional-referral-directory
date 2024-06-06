<?php
defined('ABSPATH') or die('No script kiddies please!');

require_once plugin_dir_path(__FILE__) . 'class-settings-page.php';
require_once plugin_dir_path(__FILE__) . 'referral-fee-settings.php'; // Certifique-se de incluir o arquivo onde a função está definida

// Adiciona capacidades ao papel 'service_provider' e registra menus e submenus.
function rhb_initialize_panel_menus() {
    add_action('admin_menu', 'rhb_register_menus');
    add_action('init', 'rhb_add_roles_and_capabilities');
    add_action('admin_menu', 'rhb_remove_default_dashboard_for_service_providers', 999);
    add_action('admin_init', 'rhb_handle_create_pages'); // Adiciona o handler para criar páginas
}

// Adiciona as capacidades necessárias ao papel 'service_provider'.
function rhb_add_roles_and_capabilities() {
    $role = get_role('service_provider');

    // Verifica se o papel existe antes de tentar adicionar capacidades.
    if ($role) {
        // Adiciona a capacidade de ver o dashboard do Service Provider e os contatos.
        $role->add_cap('view_rhb_dashboard');
        $role->add_cap('view_rhb_contacts');
        // Adicione outras capacidades conforme necessário aqui.
    }
}

// Registra menus e submenus no painel de administração.
function rhb_register_menus() {
    // Instancia a classe RHB_Settings aqui para uso nos callbacks.
    $rhb_plugin_settings = new RHB_Settings();

    // Submenu de Referral Fee.
    add_submenu_page(
        'edit.php?post_type=rhb_service',
        __('Referral Fees', 'referralhub'),
        __('Referral Fees', 'referralhub'),
        'manage_options',
        'rhb-referral-fee-page',
        'rhb_referral_fees_settings_page' // Esta é a função correta a ser chamada
    );

    // Adiciona um menu para o Provider Dashboard
    add_menu_page(
        __('Provider Dashboard', 'referralhub'),
        __('Dashboard', 'referralhub'),
        'view_rhb_dashboard',
        'rhb-service-provider-dashboard',
        'rhb_dashboard_page_content',
        'dashicons-businessman',
        3
    );

    // Menu de Gerenciamento de Contatos como um menu principal.
    add_menu_page(
        __('Gerenciamento de Contatos', 'referralhub'),
        __('Contatos', 'referralhub'),
        'view_rhb_contacts',
        'rhb-contacts',
        'rhb_contacts_page_content',
        'dashicons-businessman',
        6
    );

    // Submenu de Configurações Gerais utilizando a instância de RHB_Settings.
    add_submenu_page(
        'edit.php?post_type=rhb_service',
        __('Configurações Gerais', 'referralhub'),
        __('Configurações', 'referralhub'),
        'manage_options',
        'rhb-general-settings',
        [$rhb_plugin_settings, 'render_settings_page']
    );

    // Submenu para Setup Wizard.
    add_submenu_page(
        'edit.php?post_type=rhb_service',
        __('Setup Wizard', 'referralhub'),
        __('Setup Wizard', 'referralhub'),
        'manage_options',
        'rhb-setup-wizard',
        'rhb_setup_wizard_page_content'
    );

    // Registra a página de detalhes do contato como uma página 'fantasma'
    add_submenu_page(
        null, // Não exibe no menu
        __('Detalhes do Contato', 'referralhub'),
        null, // Não exibe no menu
        'view_rhb_contacts',
        'rhb-contact-details',
        'rhb_contact_details_page_content'
    );
}

function rhb_dashboard_admin_page_content() {
    include plugin_dir_path(__FILE__) . 'templates/dashboard-template-admin.php';
}

function rhb_dashboard_page_content() {
    // Inclui o arquivo que contém o conteúdo do dashboard do Service Provider
    include plugin_dir_path(__FILE__) . 'templates/dashboard-template-service-provider.php';
}

function rhb_remove_default_dashboard_for_service_providers() {
    // Verifica se o usuário atual tem o papel de 'service_provider'
    if (current_user_can('service_provider')) {
        // Remove o Dashboard padrão
        remove_menu_page('index.php');
    }
}

// Função que renderiza o conteúdo da página de Contatos.
function rhb_contacts_page_content() {
    require_once plugin_dir_path(__FILE__) . 'class-contacts-admin.php';
    $contactsPage = new Contatos_Admin_Page();
    $contactsPage->render();
}

// Função para renderizar o conteúdo da página de detalhes do contato
function rhb_contact_details_page_content() {
    require_once plugin_dir_path(__FILE__) . 'single-contact.php';
}

// Função para a página de ajuda de shortcodes.
function rhb_render_shortcodes_help_page() {
    include plugin_dir_path(__FILE__) . '/shortcodes-help-page.php';
}

// Função para a página de Setup Wizard.
function rhb_setup_wizard_page_content() {
    include plugin_dir_path(__FILE__) . '/setup-wizard.php';
}

// Função para lidar com a criação de páginas.
function rhb_handle_create_pages() {
    $inquiry_page_id = get_option('rhb_inquiry_page_id');
    $page_exists = $inquiry_page_id && get_post_status($inquiry_page_id);

    if (isset($_POST['rhb_create_pages_submit']) && check_admin_referer('rhb_create_pages', 'rhb_create_pages_nonce')) {
        if (isset($_POST['create_inquiry_page']) && !$page_exists) {
            // Cria a página de Inquiry de serviços
            $page_id = wp_insert_post([
                'post_title' => __('Inquiry de Serviços', 'referralhub'),
                'post_content' => '[rhb_inquiry_form][rhb_inquiry_results]',
                'post_status' => 'publish',
                'post_type' => 'page'
            ]);
            if ($page_id) {
                update_option('rhb_inquiry_page_id', $page_id);
                wp_redirect(admin_url('edit.php?post_type=rhb_service&page=rhb-setup-wizard&created=true'));
                exit;
            }
        }
    }
}

// Inicialização
rhb_initialize_panel_menus();
?>

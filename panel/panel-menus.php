<?php
defined('ABSPATH') or die('No script kiddies please!');

require_once plugin_dir_path(__FILE__) . 'class-settings-page.php';

// Adiciona capacidades ao papel 'service_provider' e registra menus e submenus.
function rhb_initialize_panel_menus() {
    add_action('admin_menu', 'rhb_register_menus');
    add_action('init', 'rhb_add_roles_and_capabilities');
    add_action('admin_menu', 'rhb_remove_default_dashboard_for_service_providers', 999);
   
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
        $role->add_cap('view_rhb_referral_fees');
    }
}

// Registra menus e submenus no painel de administração.
function rhb_register_menus() {
    // Instancia a classe RHB_Settings aqui para uso nos callbacks.
    $rhb_plugin_settings = new RHB_Settings();
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
        __('General Settings', 'referralhub'),
        __('Settings', 'referralhub'),
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

    // Registro do submenu de gerenciamento de invoices
    add_submenu_page(
        'edit.php?post_type=rhb_service',
        __('Manage Invoices', 'referralhub'),
        __('Invoices', 'referralhub'),
        'manage_options',
        'rhb-invoice',
        'rhb_invoice_page_content'  // A função callback que renderiza a página
    );


    // Nova página para relatórios de taxas de referência para admin
    add_submenu_page(
        'edit.php?post_type=rhb_service',
        __('Referral Fees Report', 'referralhub'),
        __('Referral Fees', 'referralhub'),
        'manage_options',
        'rhb-referral-fees',
        'rhb_referral_fees_page_content'
    );

    // Nova página para relatórios de taxas de referência para admin
    add_submenu_page(
        'edit.php?post_type=rhb_service',
        __('Referral Fees Details', 'referralhub'),
        __('Referral Fees Details', 'referralhub'),
        'manage_options',
        'rhb-referral-fees-provider-details',
        'rhb_referral_fees_provider_details_page_content'
    );

    // Nova página de taxas de referência para providers
    add_menu_page(
        __('My Referral Fees', 'referralhub'),
        __('My Referral Fees', 'referralhub'),
        'view_rhb_referral_fees',
        'rhb-my-referral-fees',
        'rhb_my_referral_fees_page_content',
        'dashicons-money',
        7
    );

    // Registra a página de detalhes do contato como uma página 'fantasma'
    add_submenu_page(
        'edit.php?post_type=rhb-contact-details', // Não exibe no menu
        __('Detalhes do Contato', 'referralhub'),
        __('Detalhes do Contato', 'referralhub'), // Não exibe no menu
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

// A função que carrega a página de invoices
function rhb_invoice_page_content() {
    $invoice_id = isset($_GET['invoice_id']) ? intval($_GET['invoice_id']) : null;
    
    if ($invoice_id) {
        include plugin_dir_path(__FILE__) . 'templates/admin-invoice-management-template.php';
    } else {
        // Caso não exista invoice_id, carregue a mesma template como nova invoice
        include plugin_dir_path(__FILE__) . 'templates/admin-invoice-management-template.php';
    }
}

function rhb_referral_fees_page_content() {
    // Inclui o arquivo de template para a página de taxas de referência dos providers
// Obtenha o caminho absoluto para o diretório do arquivo atual
$plugin_directory_path = plugin_dir_path(__FILE__);

// Construa o caminho para o arquivo template
$template_path = $plugin_directory_path . 'templates/admin-referral-fees-page-template.php';

// Tente incluir o arquivo de template
include($template_path);
}





function rhb_referral_fees_provider_details_page_content() {
    if (!current_user_can('manage_options')) {
        wp_die(__('You do not have sufficient permissions to access this page.'));
    }

    $provider_id = isset($_GET['provider_id']) ? intval($_GET['provider_id']) : 0;
    if ($provider_id) {
        $provider_data = get_provider_details($provider_id);  // Função fictícia para buscar dados
        include plugin_dir_path(__FILE__) . 'templates/admin-provider-details-template.php';
    } else {
        echo '<p>Error: Provider not found.</p>';
    }
}

function rhb_my_referral_fees_page_content() {
    // Inclui o arquivo de template para a página de taxas de referência dos providers
    include plugin_dir_path(__FILE__) . '/panel/templates/provider-admin-referral-fees-page-functions.php';
}



// Inicialização
rhb_initialize_panel_menus();
?>

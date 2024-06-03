<?php
        defined('ABSPATH') or die('No script kiddies please!');

// panel-general-customizations.php
function pdr_adjust_dashboard_menu() {
    global $submenu;

    // Checa se o usuário atual é 'service_provider'
    if (current_user_can('service_provider')) {
        // Muda a URL do Dashboard para a página do dashboard do professional
        $submenu['index.php'][0][2] = 'admin.php?page=pdr-service-provider-dashboard';
        // Remove o submenu "Home" indesejado
        unset($submenu['index.php'][0]);
    }
    // Não é necessário ajustar para administradores, pois eles seguirão para o dashboard padrão
}
add_action('admin_menu', 'pdr_adjust_dashboard_menu', 999);

function pdr_redirect_dashboard() {
    if (is_admin()) {
        $screen = get_current_screen();
        // Verifica se a tela atual é o dashboard
        if ($screen->id === "dashboard") {
            // Redireciona apenas se o usuário for 'service_provider'
            if (current_user_can('service_provider')) {
                wp_redirect(admin_url('admin.php?page=pdr-service-provider-dashboard'));
                exit;
            }
            // Não redireciona administradores, permitindo acesso ao dashboard padrão
        }
    }
}
add_action('current_screen', 'pdr_redirect_dashboard');


function pdrEnqueueCustomAdminStyle() {
    if (current_user_can('service_provider')) {
        wp_enqueue_style('pdr-custom-admin-style', plugin_dir_url(__FILE__) . '../panel/css/admin-customizations.css');
    }
}
add_action('admin_enqueue_scripts', 'pdrEnqueueCustomAdminStyle', 9999999);




// Filtro para alterar o texto padrão do rodapé Wordpress "Thank you for creating with WordPress."
function pdr_remove_footer_admin () {
    return 'PDR Plugin - By Ariel Souza';
}
add_filter('admin_footer_text', 'pdr_remove_footer_admin');


// Altera o texto sobre a versão do WordPress do rodapé no admin
function pdr_remove_footer_version() {
    return 'Version: ' . PDR_VERSION;
}
add_filter('update_footer', 'pdr_remove_footer_version', 9999);











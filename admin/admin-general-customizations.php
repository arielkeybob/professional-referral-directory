
<?php
// admin-general-customizations.php
function pdr_adjust_dashboard_menu() {
    global $submenu;

    // Checa se o usuário atual é 'professional'
    if (current_user_can('professional')) {
        // Muda a URL do Dashboard para a página do dashboard do professional
        $submenu['index.php'][0][2] = 'edit.php?post_type=professional_service&page=pdr-dashboard';
        // Remove o submenu "Home" indesejado
        unset($submenu['index.php'][0]);
    } elseif (current_user_can('administrator')) {
        // Muda a URL do Dashboard para a página do dashboard do admin
        $submenu['index.php'][0][2] = 'edit.php?post_type=professional_service&page=dashboard-admin';
        // Remove o submenu "Home" indesejado
        unset($submenu['index.php'][0]);
    }
}
add_action('admin_menu', 'pdr_adjust_dashboard_menu', 999);

function pdr_redirect_dashboard() {
    if (is_admin()) {
        $screen = get_current_screen();
        if ($screen->id === "dashboard") {
            if (current_user_can('professional')) {
                wp_redirect(admin_url('edit.php?post_type=professional_service&page=pdr-dashboard'));
                exit;
            } elseif (current_user_can('administrator')) {
                wp_redirect(admin_url('edit.php?post_type=professional_service&page=dashboard-admin'));
                exit;
            }
        }
    }
}
add_action('current_screen', 'pdr_redirect_dashboard');


function pdrEnqueueCustomAdminStyle() {
    if (current_user_can('professional')) {
        wp_enqueue_style('pdr-custom-admin-style', plugin_dir_url(__FILE__) . '../admin/css/admin-customizations.css');
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











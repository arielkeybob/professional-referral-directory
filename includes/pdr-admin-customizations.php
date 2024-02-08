
<?php
// pdr-admin-customizations.php

function pdrRemoveAdminBar() {
    if (current_user_can('professional')) {
        show_admin_bar(false);
    }
}
add_action('after_setup_theme', 'pdrRemoveAdminBar');

function pdrRemoveDashboardMenu() {
    if (current_user_can('professional')) {
        remove_menu_page('index.php'); // Remove "Dashboard"
    }
}
add_action('admin_menu', 'pdrRemoveDashboardMenu');

function pdrEnqueueCustomAdminStyle() {
    if (current_user_can('professional')) {
        wp_enqueue_style('pdr-custom-admin-style', plugin_dir_url(__FILE__) . '../admin/css/pdr-admin-customizations.css');
    }
}
add_action('admin_enqueue_scripts', 'pdrEnqueueCustomAdminStyle');




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

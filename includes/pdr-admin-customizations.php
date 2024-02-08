
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

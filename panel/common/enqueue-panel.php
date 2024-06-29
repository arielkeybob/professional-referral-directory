<?php
defined('ABSPATH') or die('No script kiddies please!');

function referralhub_enqueue_admin_styles($hook_suffix) {
    $screen = get_current_screen();
    if (strpos($hook_suffix, 'rhb') !== false || $screen->post_type === 'rhb_service' || in_array($screen->taxonomy, ['service_type', 'service_location'])) {
        // Corrige os caminhos para apontar para os diretórios admin e provider corretamente.
        wp_enqueue_style('referralhub-admin-style', plugins_url('/panel/admin/css/admin-style.css', RHB_MAIN_FILE));
        wp_enqueue_style('rhb-dashboard-admin-style', plugins_url('/panel/admin/css/dashboard-style-admin.css', RHB_MAIN_FILE));
        wp_enqueue_style('datatables-css', 'https://cdn.datatables.net/1.10.22/css/jquery.dataTables.css');
        wp_enqueue_style('rhb-admin-notifications-style', plugins_url('/panel/admin/css/admin-notifications.css', RHB_MAIN_FILE));
        wp_enqueue_style('notyf-css', 'https://unpkg.com/notyf/notyf.min.css');
        wp_enqueue_style('material-icons', 'https://fonts.googleapis.com/icon?family=Material+Icons');

        if (current_user_can('service_provider')) {
            wp_enqueue_style('rhb-custom-admin-style', plugins_url('/panel/provider/css/provider-panel.css', RHB_MAIN_FILE));
        }

        if ($hook_suffix === 'rhb_service_page_rhb-general-settings') {
            wp_enqueue_style('rhb-admin-css', plugins_url('/panel/admin/css/admin-panel.css', RHB_MAIN_FILE), array(), '1.0.0');
            wp_enqueue_style('font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css', array(), '5.15.4');
        }
    }
}

function referralhub_enqueue_admin_scripts($hook_suffix) {
    $screen = get_current_screen();
    if (strpos($hook_suffix, 'rhb') !== false || $screen->post_type === 'rhb_service' || in_array($screen->taxonomy, ['service_type', 'service_location'])) {
        // Corrige os caminhos dos scripts.
        wp_enqueue_script('dashboard-script-admin', plugins_url('/panel/admin/js/dashboard-script-admin.js', RHB_MAIN_FILE), array('jquery'), null, true);
        wp_enqueue_script('rhb-admin-notifications-script', plugins_url('/panel/admin/js/admin-notifications.js', RHB_MAIN_FILE), array('jquery'), null, true);
        wp_enqueue_script('rhb-admin-script', plugins_url('/panel/admin/js/admin-script.js', RHB_MAIN_FILE), array('jquery'), null, true);
        wp_enqueue_script('rhb-panel-ajax-script', plugins_url('/panel/common/js/rhb-panel-ajax.js', RHB_MAIN_FILE), array('jquery'), null, true);
        wp_enqueue_script('bootstrap-js', 'https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js', array('jquery'), null, true);
        wp_enqueue_script('datatables-js', 'https://cdn.datatables.net/1.10.22/js/jquery.dataTables.js', array('jquery'), null, true);
        wp_enqueue_script('chart-js', 'https://cdn.jsdelivr.net/npm/chart.js', array('jquery'), null, true);
        wp_enqueue_script('notyf-js', 'https://unpkg.com/notyf/notyf.min.js', [], null, true);

        if ($hook_suffix === 'rhb_service_page_rhb-general-settings') {
            wp_enqueue_script('rhb-settings-page-colors', plugins_url('/panel/admin/js/settings-page-colors.js', RHB_MAIN_FILE), array('jquery'), '1.0.0', true);
            wp_enqueue_script('rhb-admin-js', plugins_url('/panel/admin/js/admin-settings-manager.js', RHB_MAIN_FILE), array('jquery'), '1.0.0', true);
        }

        if (function_exists('wp_enqueue_media')) {
            wp_enqueue_media();
        } else {
            wp_enqueue_style('thickbox');
            wp_enqueue_script('media-upload');
            wp_enqueue_script('thickbox');
        }

        wp_enqueue_script('cleave-js', 'https://cdn.jsdelivr.net/npm/cleave.js@1.6.0/dist/cleave.min.js', array('jquery'), '1.6.0', true);
    }
}

add_action('admin_enqueue_scripts', 'referralhub_enqueue_admin_styles');
add_action('admin_enqueue_scripts', 'referralhub_enqueue_admin_scripts');

<?php
defined('ABSPATH') or die('No script kiddies please!');

function referralhub_enqueue_admin_styles($hook_suffix) {
    $screen = get_current_screen();
    if (strpos($hook_suffix, 'rhb') !== false || $screen->post_type === 'rhb_service' || in_array($screen->taxonomy, ['service_type', 'service_location'])) {
        wp_enqueue_style('referralhub-admin-style', plugins_url('/panel/css/admin-style.css', RHB_MAIN_FILE));
        wp_enqueue_style('rhb-dashboard-admin-style', plugins_url('/panel/css/dashboard-style-admin.css', RHB_MAIN_FILE));
        wp_enqueue_style('datatables-css', 'https://cdn.datatables.net/1.10.22/css/jquery.dataTables.css');
        wp_enqueue_style('rhb-admin-notifications-style', plugins_url('/panel/css/admin-notifications.css', RHB_MAIN_FILE));

        // Estilos específicos da página de configurações
        if ($hook_suffix === 'rhb_service_page_rhb-general-settings') {
            wp_enqueue_style('rhb-admin-css', plugin_dir_url(__FILE__) . 'css/admin-panel.css', array(), '1.0.0');
            wp_enqueue_style('font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css', array(), '5.15.4');
        }
    }
}

function referralhub_enqueue_admin_scripts($hook_suffix) {
    $screen = get_current_screen();
    if (strpos($hook_suffix, 'rhb') !== false || $screen->post_type === 'rhb_service' || in_array($screen->taxonomy, ['service_type', 'service_location'])) {
        wp_enqueue_script('dashboard-script-admin', plugins_url('/panel/js/dashboard-script-admin.js', RHB_MAIN_FILE), array('jquery'), null, true);
        wp_enqueue_script('rhb-admin-notifications-script', plugins_url('/panel/js/admin-notifications.js', RHB_MAIN_FILE), array('jquery'), null, true);
        wp_enqueue_script('rhb-admin-script', plugins_url('/panel/js/admin-script.js', RHB_MAIN_FILE), array('jquery'), null, true);
        wp_enqueue_script('rhb-panel-ajax-script', plugins_url('/panel/js/rhb-panel-ajax.js', RHB_MAIN_FILE), array('jquery'), null, true);
        wp_enqueue_script('bootstrap-js', 'https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js', array('jquery'), null, true);
        wp_enqueue_script('datatables-js', 'https://cdn.datatables.net/1.10.22/js/jquery.dataTables.js', array('jquery'), null, true);
        wp_enqueue_script('chart-js', 'https://cdn.jsdelivr.net/npm/chart.js', array('jquery'), null, true);

        // Scripts específicos da página de configurações
        if ($hook_suffix === 'rhb_service_page_rhb-general-settings') {
            wp_enqueue_script('rhb-settings-page-colors', plugin_dir_url(__FILE__) . 'js/settings-page-colors.js', array('jquery'), '1.0.0', true);
            wp_enqueue_script('rhb-admin-js', plugin_dir_url(__FILE__) . 'js/admin-settings-manager.js', array('jquery'), '1.0.0', true);
            wp_enqueue_script('google-fonts-api', 'https://fonts.googleapis.com/css2?family=Roboto&display=swap', array(), null, true);
        }
    }
}

add_action('admin_enqueue_scripts', 'referralhub_enqueue_admin_styles');
add_action('admin_enqueue_scripts', 'referralhub_enqueue_admin_scripts');

<?php
defined('ABSPATH') or die('No script kiddies please!');

// Função para enfileirar estilos específicos
function referralhub_enqueue_admin_styles($hook_suffix) {
    if (strpos($hook_suffix, 'rhb') !== false) {
        wp_enqueue_style('referralhub-admin-style', plugins_url('/panel/css/admin-style.css', RHB_MAIN_FILE));
        wp_enqueue_style('rhb-dashboard-admin-style', plugins_url('/panel/css/dashboard-style-admin.css', RHB_MAIN_FILE));
        wp_enqueue_style('datatables-css', 'https://cdn.datatables.net/1.10.22/css/jquery.dataTables.css');
    }
}

// Função para enfileirar scripts específicos
function referralhub_enqueue_admin_scripts($hook_suffix) {
    if (strpos($hook_suffix, 'rhb') !== false) {
        wp_enqueue_script('dashboard-script-admin', plugins_url('/panel/js/dashboard-script-admin.js', RHB_MAIN_FILE), array('jquery'), null, true);
        wp_enqueue_script('rhb-admin-notifications', plugins_url('/panel/js/admin-notifications.js', RHB_MAIN_FILE), array('jquery'), null, true);
        wp_enqueue_script('rhb-admin-script', plugins_url('/panel/js/admin-script.js', RHB_MAIN_FILE), array('jquery'), null, true);
        wp_enqueue_script('rhb-panel-ajax-script', plugins_url('/panel/js/rhb-panel-ajax.js', RHB_MAIN_FILE), array('jquery'), null, true);
        wp_enqueue_script('bootstrap-js', 'https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js', array('jquery'), null, true);
        wp_enqueue_script('datatables-js', 'https://cdn.datatables.net/1.10.22/js/jquery.dataTables.js', array('jquery'), null, true);
        wp_enqueue_script('chart-js', 'https://cdn.jsdelivr.net/npm/chart.js', array('jquery'), null, true);
        wp_enqueue_script('rhb-settings-page-script', plugins_url('panel/js/settings-page-colors.js', RHB_MAIN_FILE));
        
        wp_localize_script('rhb-panel-ajax-script', 'rhbPanelAjax', [
            'ajax_url' => admin_url('admin-ajax.php'),
            'ajax_nonce' => wp_create_nonce('rhb_panel_nonce')
        ]);

        wp_localize_script('dashboard-script-admin', 'rhbAjax', [
            'ajax_url' => admin_url('admin-ajax.php'),
            'ajax_nonce' => wp_create_nonce('fetch_services_nonce')
        ]);
    }
}

// Função para enfileirar ícones do Materialize
function rhb_enqueue_material_icons($hook_suffix) {
    if (strpos($hook_suffix, 'rhb') !== false) {
        wp_enqueue_style('material-icons', 'https://fonts.googleapis.com/icon?family=Material+Icons');
    }
}

// Função para enfileirar Notyf
function rhb_enqueue_notyf($hook_suffix) {
    if (strpos($hook_suffix, 'rhb') !== false) {
        wp_enqueue_style('notyf-css', 'https://unpkg.com/notyf/notyf.min.css');
        wp_enqueue_script('notyf-js', 'https://unpkg.com/notyf/notyf.min.js', [], null, true);
        wp_enqueue_script('my-custom-notyf-js', plugin_dir_url(__FILE__) . 'js/my-custom-notyf.js', ['notyf-js'], null, true);
    }
}

// Ação para enfileirar scripts e estilos no painel admin
add_action('admin_enqueue_scripts', 'referralhub_enqueue_admin_styles');
add_action('admin_enqueue_scripts', 'referralhub_enqueue_admin_scripts');
add_action('admin_enqueue_scripts', 'rhb_enqueue_material_icons');
add_action('admin_enqueue_scripts', 'rhb_enqueue_notyf');

if (is_admin()) {
    require_once plugin_dir_path(dirname(__FILE__)) . 'panel/dashboard-admin-functions.php';
}

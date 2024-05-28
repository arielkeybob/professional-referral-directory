<?php
defined('ABSPATH') or die('No script kiddies please!');

// Função para enfileirar estilos específicos
function professionaldirectory_enqueue_admin_styles($hook_suffix) {
    if (strpos($hook_suffix, 'pdr') !== false) {
        wp_enqueue_style('professionaldirectory-admin-style', plugins_url('/panel/css/admin-style.css', PDR_MAIN_FILE));
        wp_enqueue_style('pdr-dashboard-admin-style', plugins_url('/panel/css/dashboard-style-admin.css', PDR_MAIN_FILE));
        wp_enqueue_style('datatables-css', 'https://cdn.datatables.net/1.10.22/css/jquery.dataTables.css');
    }
}

// Função para enfileirar scripts específicos
function professionaldirectory_enqueue_admin_scripts($hook_suffix) {
    if (strpos($hook_suffix, 'pdr') !== false) {
        wp_enqueue_script('dashboard-script-admin', plugins_url('/panel/js/dashboard-script-admin.js', PDR_MAIN_FILE), array('jquery'), null, true);
        wp_enqueue_script('pdr-admin-notifications', plugins_url('/panel/js/admin-notifications.js', PDR_MAIN_FILE), array('jquery'), null, true);
        wp_enqueue_script('pdr-admin-script', plugins_url('/panel/js/admin-script.js', PDR_MAIN_FILE), array('jquery'), null, true);
        wp_enqueue_script('pdr-panel-ajax-script', plugins_url('/panel/js/pdr-panel-ajax.js', PDR_MAIN_FILE), array('jquery'), null, true);
        wp_enqueue_script('bootstrap-js', 'https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js', array('jquery'), null, true);
        wp_enqueue_script('datatables-js', 'https://cdn.datatables.net/1.10.22/js/jquery.dataTables.js', array('jquery'), null, true);
        wp_enqueue_script('chart-js', 'https://cdn.jsdelivr.net/npm/chart.js', array('jquery'), null, true);
        wp_enqueue_script('pdr-settings-page-script', plugins_url('panel/js/settings-page-colors.js', PDR_MAIN_FILE));
        
        wp_localize_script('pdr-panel-ajax-script', 'pdrPanelAjax', [
            'ajax_url' => admin_url('admin-ajax.php'),
            'ajax_nonce' => wp_create_nonce('pdr_panel_nonce')
        ]);

        wp_localize_script('dashboard-script-admin', 'pdrAjax', [
            'ajax_url' => admin_url('admin-ajax.php'),
            'ajax_nonce' => wp_create_nonce('fetch_services_nonce')
        ]);
    }
}

// Função para enfileirar ícones do Materialize
function pdr_enqueue_material_icons($hook_suffix) {
    if (strpos($hook_suffix, 'pdr') !== false) {
        wp_enqueue_style('material-icons', 'https://fonts.googleapis.com/icon?family=Material+Icons');
    }
}

// Função para enfileirar Notyf
function pdr_enqueue_notyf($hook_suffix) {
    if (strpos($hook_suffix, 'pdr') !== false) {
        wp_enqueue_style('notyf-css', 'https://unpkg.com/notyf/notyf.min.css');
        wp_enqueue_script('notyf-js', 'https://unpkg.com/notyf/notyf.min.js', [], null, true);
        wp_enqueue_script('my-custom-notyf-js', plugin_dir_url(__FILE__) . 'js/my-custom-notyf.js', ['notyf-js'], null, true);
    }
}

// Ação para enfileirar scripts e estilos no painel admin
add_action('admin_enqueue_scripts', 'professionaldirectory_enqueue_admin_styles');
add_action('admin_enqueue_scripts', 'professionaldirectory_enqueue_admin_scripts');
add_action('admin_enqueue_scripts', 'pdr_enqueue_material_icons');
add_action('admin_enqueue_scripts', 'pdr_enqueue_notyf');

if (is_admin()) {
    require_once plugin_dir_path(dirname(__FILE__)) . 'panel/dashboard-admin-functions.php';
}

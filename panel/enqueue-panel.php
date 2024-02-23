<?php
// Se este arquivo for chamado diretamente, aborte.
if (!defined('WPINC')) {
    die;
}




// Enfileiramento de estilos e scripts de administração
function professionaldirectory_enqueue_admin_scripts() {
    // Enfileirar os estilos de administração
    wp_enqueue_style('professionaldirectory-admin-style', plugins_url('/panel/css/admin-style.css', PDR_MAIN_FILE));
    wp_enqueue_script('dashboard-script-admin', plugins_url('/panel/js/dashboard-script-admin.js', PDR_MAIN_FILE), array('jquery'), null, true);
    wp_enqueue_script('pdr-admin-notifications', plugins_url('/panel/js/admin-notifications.js', PDR_MAIN_FILE), array('jquery'), null, true);
    wp_enqueue_style('pdr-dashboard-admin-style', plugins_url('/panel/css/dashboard-style-admin.css', PDR_MAIN_FILE));
    wp_enqueue_script('pdr-admin-script', plugins_url('/panel/js/admin-script.js', PDR_MAIN_FILE), array('jquery'), null, true);
    
    // Enfileirar o script AJAX específico do painel
    wp_enqueue_script('pdr-panel-ajax-script', plugins_url('/panel/js/pdr-panel-ajax.js', PDR_MAIN_FILE), array('jquery'), null, true);

    // Passar a URL AJAX e o nonce para o script pdr-panel-ajax
    wp_localize_script('pdr-panel-ajax-script', 'pdrPanelAjax', [
        'ajax_url' => admin_url('admin-ajax.php'),
        'ajax_nonce' => wp_create_nonce('pdr_panel_nonce') // Ação correspondente à verificação
    ]);

    

    // Passar a URL AJAX e o nonce para o script
    wp_localize_script('dashboard-script-admin', 'pdrAjax', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'ajax_nonce' => wp_create_nonce('fetch_services_nonce')
    ));
    // Enfileirar os scripts de administração
    wp_enqueue_script('bootstrap-js', 'https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js', array('jquery'), null, true);
    wp_enqueue_style('datatables-css', 'https://cdn.datatables.net/1.10.22/css/jquery.dataTables.css');
    wp_enqueue_script('datatables-js', 'https://cdn.datatables.net/1.10.22/js/jquery.dataTables.js', array('jquery'), null, true);
    wp_enqueue_script('chart-js', 'https://cdn.jsdelivr.net/npm/chart.js', array('jquery'), null, true);
    wp_enqueue_script('pdr-settings-page-script', plugins_url('panel/js/settings-page-colors.js', PDR_MAIN_FILE));
    

}

if (is_admin()) {
    require_once plugin_dir_path(dirname(__FILE__)) . 'panel/dashboard-admin-functions.php';

    // A função para enfileirar os scripts permanece a mesma
    add_action('admin_enqueue_scripts', 'professionaldirectory_enqueue_admin_scripts');
}


//Quando for separar o enfileiramento baseado nas capacidades do usuário usar como base o código abaixo
// Adicionar no arquivo includes/enqueue-panel.php
/*
function professionaldirectory_enqueue_professional_scripts() {
    if (current_user_can('professional_capability')) { // Substitua 'professional_capability' pela capacidade real
        // Enfileirar scripts e estilos específicos para "professional"
    }
}

function professionaldirectory_enqueue_admin_only_scripts() {
    if (current_user_can('manage_options')) {
        // Enfileirar scripts e estilos específicos para administradores
    }
}

add_action('admin_enqueue_scripts', 'professionaldirectory_enqueue_professional_scripts');
add_action('admin_enqueue_scripts', 'professionaldirectory_enqueue_admin_only_scripts');
*/
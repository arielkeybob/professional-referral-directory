<?php
// Se este arquivo for chamado diretamente, aborte.
if (!defined('WPINC')) {
    die;
}




// Enfileiramento de estilos e scripts de administração
function professionaldirectory_enqueue_admin_scripts() {
    // Enfileirar os estilos de administração
    wp_enqueue_style('professionaldirectory-admin-style', plugins_url('/admin/css/admin-style.css', PDR_MAIN_FILE));
    wp_enqueue_script('dashboard-script-admin', plugins_url('/admin/js/dashboard-script-admin.js', PDR_MAIN_FILE), array('jquery'), null, true);


    // Passar a URL AJAX e o nonce para o script
    wp_localize_script('dashboard-script-admin', 'myPlugin', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'ajax_nonce' => wp_create_nonce('fetch_services_nonce')
    ));
    // Enfileirar os scripts de administração
    wp_enqueue_script('bootstrap-js', 'https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js', array('jquery'), null, true);
    wp_enqueue_style('datatables-css', 'https://cdn.datatables.net/1.10.22/css/jquery.dataTables.css');
    wp_enqueue_script('datatables-js', 'https://cdn.datatables.net/1.10.22/js/jquery.dataTables.js', array('jquery'), null, true);
    wp_enqueue_script('chart-js', 'https://cdn.jsdelivr.net/npm/chart.js', array('jquery'), null, true);

}

if (is_admin()) {
    require_once plugin_dir_path(__FILE__) . '/dashboard-admin-functions.php';
    add_action('admin_enqueue_scripts', 'professionaldirectory_enqueue_admin_scripts');
}


//Quando for separar o enfileiramento baseado nas capacidades do usuário usar como base o código abaixo
// Adicionar no arquivo includes/enqueue-admin.php
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
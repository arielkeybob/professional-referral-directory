<?php
/*
Plugin Name: ReferralHub
Plugin URI: http://arielsouza.com.br/referralhub
Description: Manages a directory of Service Providers and listings.
Version: 1.1.5
Author: Ariel Souza
Author URI: arielsouza.com.br
License: GPLv2 or later
Text Domain: referralhub
*/

// Prevenção contra acesso direto ao arquivo.
defined('ABSPATH') or die('No script kiddies please!');

define('PDR_MAIN_FILE', __FILE__);

define( 'PDR_VERSION', '1.1.5' ); 


// Inclusões de Arquivos Principais do Plugin
require_once plugin_dir_path(__FILE__) . 'update.php';
require_once plugin_dir_path(__FILE__) . 'includes/class-pdr-users.php';
require_once plugin_dir_path(__FILE__) . 'includes/class-pdr-cpt.php';
require_once plugin_dir_path(__FILE__) . 'includes/class-pdr-taxonomies.php';
require_once plugin_dir_path(__FILE__) . 'panel/class-panel-restrictions.php';
require_once plugin_dir_path(__FILE__) . 'includes/class-pdr-metaboxes.php';
require_once plugin_dir_path(__FILE__) . 'public/form-data-functions.php';
require_once plugin_dir_path(__FILE__) . 'includes/email-functions.php';
require_once plugin_dir_path(__FILE__) . 'public/class-pdr-inquiry-form.php';
require_once plugin_dir_path(__FILE__) . 'public/class-pdr-inquiry-results.php';
//require_once plugin_dir_path(__FILE__) . 'panel/class-settings-page.php'; //Já é incluido diretamente no panel/panel-menus.php
require_once plugin_dir_path(__FILE__) . 'includes/data-storage-functions.php';
require_once plugin_dir_path(__FILE__) . 'panel/dashboard-service-provider-functions.php';
require_once plugin_dir_path(__FILE__) . 'includes/activation.php'; // Inclusão do novo arquivo de ativação
require_once plugin_dir_path(__FILE__) . 'panel/panel-menus.php';
require_once plugin_dir_path(__FILE__) . 'public/enqueue-public.php';
require_once plugin_dir_path(__FILE__) . 'panel/enqueue-panel.php';
require_once plugin_dir_path(__FILE__) . 'panel/panel-notifications.php';
require_once plugin_dir_path(__FILE__) . 'includes/global-styles.php';
include_once plugin_dir_path(__FILE__) . 'panel/panel-general-customizations.php';
include_once plugin_dir_path(__FILE__) . 'panel/panel-top-bar-customizations.php';
require_once plugin_dir_path(__FILE__) . 'includes/ajax-handlers.php';



// Verificação e execução do script de atualização
function pdrCheckVersion() {
    $installed_ver = get_option('pdr_db_version');
    if ($installed_ver != PDR_VERSION) {
        require_once(plugin_dir_path(__FILE__) . 'update.php');
        pdr_update_plugin(); // Função do update.php
        update_option('pdr_db_version', PDR_VERSION);
    }
}
add_action('plugins_loaded', 'pdrCheckVersion');



// Inclui as classes do plugin

function pdrActivate() {
    pdrActivatePlugin(); // Esta função está definida em activation.php.
}
register_activation_hook(__FILE__, 'pdrActivate');



// Enfileirando o carregador de mídia
function pdr_enqueue_media_uploader() {
    if (function_exists('wp_enqueue_media')) {
        wp_enqueue_media();
    } else {
        wp_enqueue_style('thickbox');
        wp_enqueue_script('media-upload');
        wp_enqueue_script('thickbox');
    }
}
add_action('admin_enqueue_scripts', 'pdr_enqueue_media_uploader');



function pdrDeactivate() {
    PDR_Users::cleanup_user_roles();
}
register_deactivation_hook(__FILE__, 'pdrDeactivate');




//AJAX salvar contato   
// Adicionar o hook apenas se o contexto atual for uma requisição AJAX.
if (defined('DOING_AJAX') && DOING_AJAX) {
    add_action('wp_ajax_save_contact_details', 'pdr_save_contact_details_ajax_handler');
}



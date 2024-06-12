<?php
/*
Plugin Name: ReferralHub
Plugin URI: http://arielsouza.com.br/referralhub
Description: Manages a directory of Service Providers and listings.
Version: 1.1.7
Author: Ariel Souza
Author URI: arielsouza.com.br
License: GPLv2 or later
Text Domain: referralhub
*/

// Prevenção contra acesso direto ao arquivo.
defined('ABSPATH') or die('No script kiddies please!');

define('RHB_MAIN_FILE', __FILE__);

define( 'RHB_VERSION', '1.1.7' ); 


// Inclusões de Arquivos Principais do Plugin
require_once plugin_dir_path(__FILE__) . 'update.php';
require_once plugin_dir_path(__FILE__) . 'includes/class-rhb-users.php';
require_once plugin_dir_path(__FILE__) . 'includes/class-rhb-cpt.php';
require_once plugin_dir_path(__FILE__) . 'includes/class-rhb-taxonomies.php';
require_once plugin_dir_path(__FILE__) . 'panel/class-panel-restrictions.php';
require_once plugin_dir_path(__FILE__) . 'includes/class-rhb-metaboxes.php';
require_once plugin_dir_path(__FILE__) . 'public/form-data-functions.php';
require_once plugin_dir_path(__FILE__) . 'includes/email-functions.php';
require_once plugin_dir_path(__FILE__) . 'public/class-rhb-inquiry-form.php';
require_once plugin_dir_path(__FILE__) . 'public/class-rhb-inquiry-results.php';
//require_once plugin_dir_path(__FILE__) . 'panel/class-settings-page.php'; //Já é incluido diretamente no panel/panel-menus.php
require_once plugin_dir_path(__FILE__) . 'includes/data-storage-functions.php';
require_once plugin_dir_path(__FILE__) . 'panel/dashboard-service-provider-functions.php';
require_once plugin_dir_path(__FILE__) . 'includes/activation.php'; 
require_once plugin_dir_path(__FILE__) . 'panel/panel-menus.php';
require_once plugin_dir_path(__FILE__) . 'public/enqueue-public.php';
require_once plugin_dir_path(__FILE__) . 'panel/enqueue-panel.php';
require_once plugin_dir_path(__FILE__) . 'panel/panel-notifications.php';
require_once plugin_dir_path(__FILE__) . 'includes/global-styles.php';
include_once plugin_dir_path(__FILE__) . 'panel/panel-general-customizations.php';
include_once plugin_dir_path(__FILE__) . 'panel/panel-top-bar-customizations.php';
require_once plugin_dir_path(__FILE__) . 'includes/ajax-handlers.php';
require_once plugin_dir_path(__FILE__) . 'includes/class-rhb-media-restrictions.php';



// Verificação e execução do script de atualização
function rhbCheckVersion() {
    $installed_ver = get_option('rhb_db_version');
    if ($installed_ver != RHB_VERSION) {
        require_once(plugin_dir_path(__FILE__) . 'update.php');
        rhb_update_plugin(); // Função do update.php
        update_option('rhb_db_version', RHB_VERSION);
    }
}
add_action('plugins_loaded', 'rhbCheckVersion');



// Inclui as classes do plugin

function rhbActivate() {
    rhbActivatePlugin(); // Esta função está definida em activation.php.
}
register_activation_hook(__FILE__, 'rhbActivate');



// Enfileirando o carregador de mídia
function rhb_enqueue_media_uploader() {
    if (function_exists('wp_enqueue_media')) {
        wp_enqueue_media();
    } else {
        wp_enqueue_style('thickbox');
        wp_enqueue_script('media-upload');
        wp_enqueue_script('thickbox');
    }
}
add_action('admin_enqueue_scripts', 'rhb_enqueue_media_uploader');



function rhbDeactivate() {
    RHB_Users::cleanup_user_roles();
}
register_deactivation_hook(__FILE__, 'rhbDeactivate');




//AJAX salvar contato   
// Adicionar o hook apenas se o contexto atual for uma requisição AJAX.
if (defined('DOING_AJAX') && DOING_AJAX) {
    add_action('wp_ajax_save_contact_details', 'rhb_save_contact_details_ajax_handler');
}


// Adiciona links adicionais na lista de plugins
function rhb_add_plugin_action_links($links) {
    $settings_link = '<a href="' . admin_url('edit.php?post_type=rhb_service&page=rhb-general-settings') . '">' . __('Settings', 'referralhub') . '</a>';
    $docs_link = '<a href="https://github.com/arielkeybob/professional-referral-directory/tree/main/docs" target="_blank">' . __('Docs', 'referralhub') . '</a>';
    
    // Adiciona seus links no início do array de links
    array_unshift($links, $settings_link, $docs_link);
    return $links;
}
add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'rhb_add_plugin_action_links');





<?php
/*
Plugin Name: ProfessionalDirectory
Plugin URI: http://arielsouza.com.br/professionaldirectory
Description: Manages a directory of professional services and listings.
Version: 1.1
Author: Ariel Souza
Author URI: arielsouza.com.br
License: GPLv2 or later
Text Domain: professionaldirectory
*/

// Prevenção contra acesso direto ao arquivo.
defined('ABSPATH') or die('No script kiddies please!');

define('PDR_MAIN_FILE', __FILE__);

define( 'PDR_VERSION', '1.1.0' ); // Substitua 1.0.0 pela versão atual do seu plugin


// Inclusões de Arquivos Principais do Plugin
require_once plugin_dir_path(__FILE__) . 'includes/class-pdr-users.php';
require_once plugin_dir_path(__FILE__) . 'includes/class-pdr-cpt.php';
require_once plugin_dir_path(__FILE__) . 'includes/class-pdr-taxonomies.php';
require_once plugin_dir_path(__FILE__) . 'panel/class-panel-restrictions.php';
require_once plugin_dir_path(__FILE__) . 'includes/class-pdr-metaboxes.php';
require_once plugin_dir_path(__FILE__) . 'public/form-data-functions.php';
require_once plugin_dir_path(__FILE__) . 'includes/email-functions.php';
require_once plugin_dir_path(__FILE__) . 'public/class-pdr-search-form.php';
require_once plugin_dir_path(__FILE__) . 'public/class-pdr-search-results.php';
require_once plugin_dir_path(__FILE__) . 'panel/class-settings-page.php';
require_once plugin_dir_path(__FILE__) . 'includes/data-storage-functions.php';
require_once plugin_dir_path(__FILE__) . 'panel/dashboard-professional-functions.php';
require_once plugin_dir_path(__FILE__) . 'includes/activation.php'; // Inclusão do novo arquivo de ativação
require_once plugin_dir_path(__FILE__) . 'panel/panel-menus.php';
require_once plugin_dir_path(__FILE__) . 'public/enqueue-public.php';
require_once plugin_dir_path(__FILE__) . 'panel/enqueue-panel.php';
require_once plugin_dir_path(__FILE__) . 'panel/panel-notifications.php';
require_once plugin_dir_path(__FILE__) . 'includes/global-styles.php';
include_once plugin_dir_path(__FILE__) . 'panel/panel-general-customizations.php';
include_once plugin_dir_path(__FILE__) . 'panel/panel-top-bar-customizations.php';
// Inclui as classes do plugin
require_once plugin_dir_path(__FILE__) . 'includes/class-contacts-cpt.php';
require_once plugin_dir_path(__FILE__) . 'includes/class-contacts-metabox.php';
require_once plugin_dir_path(__FILE__) . 'public/class-contacts-public.php';



// Inicializa as classes
function seu_plugin_init() {
    $cpt = new Contatos_CPT();
    $metabox = new Contatos_Metabox();
    
    $public = new Contatos_Public();
}

add_action('plugins_loaded', 'seu_plugin_init');




// Instanciar a classe de administração
if (is_admin()) {
    $pdr_plugin_settings = new PDR_Settings();
}

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

function pdrActivate() {
    PDR_Users::initialize_user_roles();
    pdrCreateSearchDataTable(); // Chamada existente do arquivo activation.php
    update_option( 'pdr_version', PDR_VERSION ); // Armazena a versão atual do plugin
    pdrCheckVersion();
    pdrStartSession();
}
register_activation_hook(__FILE__, 'pdrActivate');



function pdrDeactivate() {
    PDR_Users::cleanup_user_roles();
}
register_deactivation_hook(__FILE__, 'pdrDeactivate');





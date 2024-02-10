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
require_once plugin_dir_path(__FILE__) . 'admin/class-pdr-admin.php';
require_once plugin_dir_path(__FILE__) . 'includes/class-pdr-metaboxes.php';
require_once plugin_dir_path(__FILE__) . 'public/form-data-functions.php';
require_once plugin_dir_path(__FILE__) . 'includes/email-functions.php';
require_once plugin_dir_path(__FILE__) . 'public/class-pdr-search-form.php';
require_once plugin_dir_path(__FILE__) . 'public/class-pdr-search-results.php';
require_once plugin_dir_path(__FILE__) . 'admin/class-prd-settings-page.php';
require_once plugin_dir_path(__FILE__) . 'includes/data-storage-functions.php';
require_once plugin_dir_path(__FILE__) . 'admin/dashboard-professional-functions.php';
require_once plugin_dir_path(__FILE__) . 'includes/activation.php'; // Inclusão do novo arquivo de ativação
require_once plugin_dir_path(__FILE__) . 'admin/admin-menus.php';
require_once plugin_dir_path(__FILE__) . 'public/enqueue-public.php';
require_once plugin_dir_path(__FILE__) . 'admin/enqueue-admin.php';
require_once plugin_dir_path(__FILE__) . 'admin/notifications.php';
require_once plugin_dir_path(__FILE__) . 'includes/global-styles.php';
include_once plugin_dir_path(__FILE__) . 'includes/pdr-admin-customizations.php';
include_once plugin_dir_path(__FILE__) . 'admin/pdr-admin-bar-customizations.php';




// Instanciar a classe de administração
if (is_admin()) {
    $pdr_plugin_settings = new PDR_Settings();
}

function pdrActivate() {
    ProfessionalDirectory_Users::initialize_user_roles();
    pdrCreateSearchDataTable(); // Chamada existente do arquivo activation.php
    update_option( 'pdr_version', PDR_VERSION ); // Armazena a versão atual do plugin
    pdrCheckVersion();
    pdrStartSession();
}
register_activation_hook(__FILE__, 'pdrActivate');



function pdrDeactivate() {
    ProfessionalDirectory_Users::cleanup_user_roles();
}
register_deactivation_hook(__FILE__, 'pdrDeactivate');





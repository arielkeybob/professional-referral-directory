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

// Inclusões de Arquivos Principais do Plugin
require_once plugin_dir_path(__FILE__) . 'includes/class-pdr-users.php';
require_once plugin_dir_path(__FILE__) . 'includes/class-pdr-cpt.php';
require_once plugin_dir_path(__FILE__) . 'includes/class-pdr-admin.php';
require_once plugin_dir_path(__FILE__) . 'includes/class-pdr-metaboxes.php';
require_once plugin_dir_path(__FILE__) . 'includes/form-data-functions.php';
require_once plugin_dir_path(__FILE__) . 'includes/email-functions.php';
require_once plugin_dir_path(__FILE__) . 'public/class-pdr-search-form.php';
require_once plugin_dir_path(__FILE__) . 'public/class-pdr-search-results.php';
require_once plugin_dir_path(__FILE__) . 'admin/class-myplugin-admin.php';
require_once plugin_dir_path(__FILE__) . 'includes/data-storage-functions.php';
require_once plugin_dir_path(__FILE__) . 'includes/dashboard-professional-functions.php';
require_once plugin_dir_path(__FILE__) . 'includes/activation.php'; // Inclusão do novo arquivo de ativação
require_once plugin_dir_path(__FILE__) . 'admin/admin-menus.php';
require_once plugin_dir_path(__FILE__) . 'includes/enqueue-public.php';
require_once plugin_dir_path(__FILE__) . 'includes/enqueue-admin.php';



// Instanciar a classe de administração
if (is_admin()) {
    $myplugin_admin = new MyPlugin_Admin();
}

// Hooks para ativação e desativação do plugin
function professional_directory_activate() {
    ProfessionalDirectory_Users::activate();
    pdr_create_search_data_table(); // Agora chamada do arquivo activation.php
}
register_activation_hook(__FILE__, 'professional_directory_activate');

function professional_directory_deactivate() {
    ProfessionalDirectory_Users::deactivate();
}
register_deactivation_hook(__FILE__, 'professional_directory_deactivate');

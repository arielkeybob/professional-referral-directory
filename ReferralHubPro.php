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
define('RHB_VERSION', '1.1.7'); 

// Inclusões de Arquivos Principais do Plugin
require_once plugin_dir_path(__FILE__) . 'includes/classes/class-rhb-users.php';
require_once plugin_dir_path(__FILE__) . 'includes/classes/class-rhb-cpt.php';
require_once plugin_dir_path(__FILE__) . 'includes/classes/class-rhb-taxonomies.php';
require_once plugin_dir_path(__FILE__) . 'includes/classes/class-rhb-metaboxes.php';
require_once plugin_dir_path(__FILE__) . 'includes/classes/class-rhb-media-restrictions.php';
require_once plugin_dir_path(__FILE__) . 'includes/ajax-handlers.php';
require_once plugin_dir_path(__FILE__) . 'includes/data-storage-functions.php';
require_once plugin_dir_path(__FILE__) . 'includes/email-functions.php';
require_once plugin_dir_path(__FILE__) . 'includes/activation.php';
require_once plugin_dir_path(__FILE__) . 'includes/global-styles.php';
require_once plugin_dir_path(__FILE__) . 'update.php';

// Inclusões do painel
require_once plugin_dir_path(__FILE__) . 'panel/common/class-panel-restrictions.php';
require_once plugin_dir_path(__FILE__) . 'panel/common/panel-general-customizations.php';
require_once plugin_dir_path(__FILE__) . 'panel/common/panel-top-bar-customizations.php';
require_once plugin_dir_path(__FILE__) . 'panel/common/enqueue-panel.php';
require_once plugin_dir_path(__FILE__) . 'panel/common/panel-notifications.php';
require_once plugin_dir_path(__FILE__) . 'panel/common/common-menus.php';
;


// Inclusões específicas do admin
require_once plugin_dir_path(__FILE__) . 'panel/admin/admin-provider-details-functions.php';
require_once plugin_dir_path(__FILE__) . 'panel/admin/admin-referral-fees-page-functions.php';
require_once plugin_dir_path(__FILE__) . 'panel/admin/admin-invoice-functions.php';
require_once plugin_dir_path(__FILE__) . 'panel/admin/admin-menus.php';


// Inclusões específicas do provedor
require_once plugin_dir_path(__FILE__) . 'panel/provider/dashboard-service-provider-functions.php';
require_once plugin_dir_path(__FILE__) . 'panel/provider/provider-menus.php';

// Inclusões públicas
require_once plugin_dir_path(__FILE__) . 'public/class-rhb-inquiry-form.php';
require_once plugin_dir_path(__FILE__) . 'public/class-rhb-inquiry-results.php';
require_once plugin_dir_path(__FILE__) . 'public/form-data-functions.php';
require_once plugin_dir_path(__FILE__) . 'public/enqueue-public.php';

// Verificação e execução do script de atualização
function rhbCheckVersion() {
    $installed_ver = get_option('rhb_db_version');
    if ($installed_ver != RHB_VERSION) {
        rhb_update_plugin();
        update_option('rhb_db_version', RHB_VERSION);
    }
}
add_action('plugins_loaded', 'rhbCheckVersion');

// Inclui as classes do plugin
function rhbActivate() {
    rhbActivatePlugin();
}
register_activation_hook(__FILE__, 'rhbActivate');

function rhbDeactivate() {
    RHB_Users::cleanup_user_roles();
}
register_deactivation_hook(__FILE__, 'rhbDeactivate');

// AJAX salvar contato
if (defined('DOING_AJAX') && DOING_AJAX) {
    add_action('wp_ajax_save_contact_details', 'rhb_save_contact_details_ajax_handler');
}

// Adiciona links adicionais na lista de plugins
function rhb_add_plugin_action_links($links) {
    $settings_link = '<a href="' . admin_url('edit.php?post_type=rhb_service&page=rhb-general-settings') . '">' . __('Settings', 'referralhub') . '</a>';
    $docs_link = '<a href="https://github.com/arielkeybob/professional-referral-directory/tree/main/docs" target="_blank">' . __('Docs', 'referralhub') . '</a>';
    array_unshift($links, $settings_link, $docs_link);
    return $links;
}
add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'rhb_add_plugin_action_links');

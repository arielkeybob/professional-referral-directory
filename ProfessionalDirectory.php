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

function pdrActivate() {
    pdrActivatePlugin(); // Esta função está definida em activation.php.
    if (class_exists('PDR_Users')) {
        PDR_Users::initialize_user_roles();
    }
    update_option('pdr_version', PDR_VERSION);
}

register_activation_hook(__FILE__, 'pdrActivate');




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







function pdrDeactivate() {
    PDR_Users::cleanup_user_roles();
}
register_deactivation_hook(__FILE__, 'pdrDeactivate');







add_action('wp_ajax_save_contact_details', function() {
    // Certifique-se de que o nonce foi enviado e é válido
    check_ajax_referer('update_contact_' . $_POST['contact_id'], 'nonce');

    // Verifica se o usuário tem permissão para executar esta ação
    if (!current_user_can('view_pdr_contacts')) {
        wp_send_json_error(['message' => 'Permissão insuficiente.']);
        exit;
    }

    global $wpdb;
    $contact_id = isset($_POST['contact_id']) ? intval($_POST['contact_id']) : 0;
    $author_id = get_current_user_id(); // Obtém o ID do autor atual
    $new_status = isset($_POST['contact_status']) ? sanitize_text_field($_POST['contact_status']) : '';
    $custom_name = isset($_POST['custom_name']) ? sanitize_text_field($_POST['custom_name']) : '';

    // Atualiza o status e o nome customizado do contato se o autor bate com o atual
    $updated = $wpdb->update(
        "{$wpdb->prefix}pdr_author_contact_relations",
        ['status' => $new_status, 'custom_name' => $custom_name],
        [
            'contact_id' => $contact_id,
            'author_id' => $author_id // Condição adicional para o author_id
        ]
    );

    if (false === $updated) {
        error_log('Erro ao atualizar o contato: ' . $wpdb->last_error);
        wp_send_json_error(['message' => 'Erro ao atualizar o contato.']);
        exit;
    }

    // Atualiza o status das pesquisas se o autor bate com o atual
    $errors = false;
    foreach ($_POST['searches'] as $search_id => $search_status) {
        $search_id_sanitized = intval($search_id);
        $status_sanitized = sanitize_text_field($search_status);

        $search_updated = $wpdb->update(
            "{$wpdb->prefix}pdr_search_data",
            ['search_status' => $status_sanitized],
            [
                'id' => $search_id_sanitized,
                'author_id' => $author_id // Condição adicional para o author_id
            ]
        );

        if (false === $search_updated) {
            error_log("Erro ao atualizar o status da pesquisa ID $search_id: " . $wpdb->last_error);
            $errors = true;
        }
    }

    if ($errors) {
        wp_send_json_error(['message' => 'Erro ao atualizar o status de algumas ou todas as pesquisas.']);
    } else {
        wp_send_json_success(['message' => 'Informações atualizadas com sucesso.']);
    }

    exit;
});

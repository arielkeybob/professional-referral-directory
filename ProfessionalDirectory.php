<?php
/*
Plugin Name: ProfessionalDirectory
Plugin URI: http://arielsouza.com.br/professionaldirectory
Description: Gerencia um diretório de serviços profissionais e listagens.
Version: 1.0
Author: Ariel Souza
Author URI: arielsouza.com.br
License: GPLv2 or later
Text Domain: professionaldirectory
*/

// Prevenção contra acesso direto ao arquivo.
defined('ABSPATH') or die('No script kiddies please!');

// Inclusões de Arquivos Principais do Plugin
require_once plugin_dir_path(__FILE__) . 'includes/class-pdr-users.php';
require_once plugin_dir_path(__FILE__) . 'includes/class-pdr-cpt.php';
require_once plugin_dir_path(__FILE__) . 'includes/class-pdr-admin.php';
require_once plugin_dir_path(__FILE__) . 'includes/class-pdr-metaboxes.php';
require_once plugin_dir_path(__FILE__) . 'public/class-pdr-search-form.php';
require_once plugin_dir_path(__FILE__) . 'public/class-pdr-search-results.php';

// Hooks para ativação e desativação do plugin
function professional_directory_activate() {
    ProfessionalDirectory_Users::activate();
}
register_activation_hook(__FILE__, 'professional_directory_activate');

function professional_directory_deactivate() {
    ProfessionalDirectory_Users::deactivate();
}
register_deactivation_hook(__FILE__, 'professional_directory_deactivate');

// Hook para registrar o Custom Post Type (CPT) e taxonomia
add_action('init', ['ProfessionalDirectory_CPT', 'register_service_cpt']);
add_action('init', ['ProfessionalDirectory_CPT', 'register_service_type_taxonomy']);

// Enfileiramento de estilos e scripts públicos
function professionaldirectory_enqueue_scripts() {
    wp_enqueue_style('professionaldirectory-style', plugins_url('/public/css/style.css', __FILE__));
    wp_enqueue_script('professionaldirectory-script', plugins_url('/public/js/script.js', __FILE__), array('jquery'), '', true);
    wp_enqueue_script('pdr-search-script', plugins_url('/public/js/search.js', __FILE__), array('jquery'), null, true);

    // Correção: Passar 'ajaxurl' como array
    wp_localize_script('pdr-search-script', 'ajax_object', array( 'ajax_url' => admin_url('admin-ajax.php') ));
}
add_action('wp_enqueue_scripts', 'professionaldirectory_enqueue_scripts');

// Enfileiramento de estilos e scripts de administração
function professionaldirectory_enqueue_admin_scripts() {
    wp_enqueue_style('professionaldirectory-admin-style', plugins_url('/admin/css/admin-style.css', __FILE__));
    wp_enqueue_script('professionaldirectory-admin-script', plugins_url('/admin/js/admin-script.js', __FILE__), array('jquery'), '', true);
}
if (is_admin()) {
    add_action('admin_enqueue_scripts', 'professionaldirectory_enqueue_admin_scripts');
}

// Configuração de capacidades para o CPT
function professional_directory_set_capabilities() {
    ProfessionalDirectory_CPT::set_service_capabilities();
}
add_action('init', 'professional_directory_set_capabilities', 11);

// Adicionando ações para lidar com a requisição AJAX
add_action('wp_ajax_pdr_search', 'pdr_search_callback');
add_action('wp_ajax_nopriv_pdr_search', 'pdr_search_callback');

function pdr_search_callback() {
    $service_type = isset($_POST['service_type']) ? sanitize_text_field($_POST['service_type']) : '';

    $args = array(
        'post_type' => 'professional_service', // Substitua pelo seu tipo de post correto
        'tax_query' => array(
            array(
                'taxonomy' => 'service_type', // Substitua pela sua taxonomia correta
                'field'    => 'slug',
                'terms'    => $service_type,
            ),
        ),
    );

    $query = new WP_Query($args);

    if ($query->have_posts()) {
        // Supondo que você deseja retornar uma lista de posts encontrados
        $services = array();
        while ($query->have_posts()) {
            $query->the_post();
            $services[] = array(
                'id' => get_the_ID(),
                'title' => get_the_title(),
                // Inclua outros detalhes do serviço conforme necessário
            );
        }
        wp_reset_postdata();
        wp_send_json_success($services);
    } else {
        wp_send_json_error('Nenhum serviço encontrado.');
    }

    wp_die();
}

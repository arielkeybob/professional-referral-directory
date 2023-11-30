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
require_once plugin_dir_path(__FILE__) . 'includes/form-data-functions.php'; // Inclui a função comum para captura de dados
require_once plugin_dir_path(__FILE__) . 'includes/email-functions.php'; // Inclui as funções de e-mail
require_once plugin_dir_path(__FILE__) . 'public/class-pdr-search-form.php';
require_once plugin_dir_path(__FILE__) . 'public/class-pdr-search-results.php';
require_once plugin_dir_path(__FILE__) . 'admin/class-myplugin-admin.php';
require_once plugin_dir_path(__FILE__) . 'includes/data-storage-functions.php';


// Instanciar a classe de administração
if (is_admin()) {
    $myplugin_admin = new MyPlugin_Admin();
}

// Hooks para ativação e desativação do plugin
function professional_directory_activate() {
    ProfessionalDirectory_Users::activate();
    pdr_create_search_data_table(); // Criação da tabela ao ativar o plugin
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
    
    // Recupera a chave da API do Google Maps das opções do plugin
    $google_maps_api_key = get_option('myplugin_google_maps_api_key');

    // Enfileira o script do seu plugin
    wp_enqueue_script('professionaldirectory-script', plugins_url('/public/js/script.js', __FILE__), array('jquery'), '', true);

    // Enfileira o script do Google Maps com a biblioteca Places
    // Certifique-se de que o script do Google Maps seja carregado após o seu script.js
    wp_enqueue_script('google-maps', 'https://maps.googleapis.com/maps/api/js?key=' . $google_maps_api_key . '&libraries=places&callback=initAutocomplete', array('professionaldirectory-script'), null, true);

    // Enfileira o script específico de pesquisa
    wp_enqueue_script('pdr-search-script', plugins_url('/public/js/search.js', __FILE__), array('jquery', 'google-maps'), null, true);

    // Localize o script para disponibilizar a URL do AJAX para o JavaScript
    wp_localize_script('pdr-search-script', 'ajax_object', array('ajax_url' => admin_url('admin-ajax.php')));
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
        'post_type' => 'professional_service',
        'tax_query' => array(
            array(
                'taxonomy' => 'service_type',
                'field'    => 'slug',
                'terms'    => $service_type,
            ),
        ),
    );

    $query = new WP_Query($args);

    if ($query->have_posts()) {
        ob_start();

        while ($query->have_posts()) {
            $query->the_post();
            include plugin_dir_path(__FILE__) . 'public/templates/content-service.php';
        }

        $html = ob_get_clean();

        $query->rewind_posts();
        if ($query->have_posts()) {
            $query->the_post();
            $post_id = get_the_ID();
            $author_id = get_post_field('post_author', $post_id);

            send_email_to_service_author($post_id);

            $user_data = get_form_data();
            $user_data['service_id'] = $post_id;
            $user_data['author_id'] = $author_id;
            $user_data['search_date'] = current_time('mysql');

            store_search_data($user_data);
        }

        wp_reset_postdata();
        wp_send_json_success($html);
    } else {
        wp_send_json_error('Nenhum serviço encontrado.');
    }

    wp_die();
}



function pdr_create_search_data_table() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'pdr_search_data';
    $charset_collate = $wpdb->get_charset_collate();

    // SQL para criar ou modificar a tabela
    $sql = "CREATE TABLE IF NOT EXISTS $table_name (
        id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        service_type VARCHAR(255) NOT NULL,
        name VARCHAR(255),
        email VARCHAR(255),
        address VARCHAR(255),
        search_date DATETIME NOT NULL,
        service_id BIGINT UNSIGNED,
        author_id BIGINT UNSIGNED
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);

    // Verifica e cria índice para author_id, se não existir
    if(!$wpdb->query("SHOW INDEX FROM $table_name WHERE Key_name = 'idx_author_id'")) {
        $wpdb->query("CREATE INDEX idx_author_id ON $table_name (author_id)");
    }

    // Verifica e cria índice para service_id, se não existir
    if(!$wpdb->query("SHOW INDEX FROM $table_name WHERE Key_name = 'idx_service_id'")) {
        $wpdb->query("CREATE INDEX idx_service_id ON $table_name (service_id)");
    }
}

register_activation_hook(__FILE__, 'pdr_create_search_data_table');


register_activation_hook(__FILE__, 'pdr_create_search_data_table');


register_activation_hook(__FILE__, 'pdr_create_search_data_table');

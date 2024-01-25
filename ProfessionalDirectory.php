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


// Hook para registrar o Custom Post Type (CPT) e taxonomia
add_action('init', ['ProfessionalDirectory_CPT', 'register_service_cpt']);
add_action('init', ['ProfessionalDirectory_CPT', 'register_service_type_taxonomy']);









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
    $service_location = isset($_POST['service_location']) ? sanitize_text_field($_POST['service_location']) : '';

    $args = array(
        'post_type' => 'professional_service',
        'tax_query' => array(
            'relation' => 'AND',
            array(
                'taxonomy' => 'service_type',
                'field'    => 'slug',
                'terms'    => $service_type,
            ),
            array(
                'taxonomy' => 'service_location',
                'field'    => 'slug',
                'terms'    => $service_location,
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
        wp_send_json_error('No service found.');
    }

    wp_die();
}
<?php
/**
 * Classe para gerenciar as taxonomias do ProfessionalDirectory.
 */

if (!defined('WPINC')) {
    die;
}

class PDR_Taxonomies {
    /**
     * Registra a taxonomia Tipo de Serviço.
     */
    public static function register_service_type_taxonomy() {
        $labels = array(
            'name'              => _x( 'Service Types', 'taxonomy general name', 'professionaldirectory' ),
            'singular_name'     => _x( 'Service Type', 'taxonomy singular name', 'professionaldirectory' ),
            'search_items'      => __( 'Search Service Types', 'professionaldirectory' ),
            'all_items'         => __( 'All Service Types', 'professionaldirectory' ),
            'parent_item'       => __( 'Parent Service Type', 'professionaldirectory' ),
            'parent_item_colon' => __( 'Parent Service Type:', 'professionaldirectory' ),
            'edit_item'         => __( 'Edit Service Type', 'professionaldirectory' ),
            'update_item'       => __( 'Update Service Type', 'professionaldirectory' ),
            'add_new_item'      => __( 'Add New Service Type', 'professionaldirectory' ),
            'new_item_name'     => __( 'New Service Type Name', 'professionaldirectory' ),
            'menu_name'         => __( 'Service Type', 'professionaldirectory' ),
        );

        $args = array(
            'hierarchical'      => true, // Define se a taxonomia é hierárquica como categorias ou não hierárquica como tags.
            'labels'            => $labels,
            'show_ui'           => true,
            'show_in_menu'      => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'rewrite'           => array( 'slug' => 'service-type' ),
            'capabilities'      => array(
                'manage_terms' => 'manage_options', // Utiliza a capacidade 'manage_options' para gerenciar termos
                'edit_terms'   => 'manage_options',
                'delete_terms' => 'manage_options',
                'assign_terms' => 'edit_professional_services', // Capacidade para atribuir termos
            ),
        );

        register_taxonomy('service_type', 'professional_service', $args);
    }

    /**
     * Registra a taxonomia Localização do Serviço.
     */
    public static function register_service_location_taxonomy() { 
        $labels = array(
            'name'              => _x( 'Service Locations', 'taxonomy general name', 'professionaldirectory' ),
            'singular_name'     => _x( 'Service Location', 'taxonomy singular name', 'professionaldirectory' ),
            'search_items'      => __( 'Search Service Locations', 'professionaldirectory' ),
            'all_items'         => __( 'All Service Locations', 'professionaldirectory' ),
            'parent_item'       => __( 'Parent Service Location', 'professionaldirectory' ),
            'parent_item_colon' => __( 'Parent Service Location:', 'professionaldirectory' ),
            'edit_item'         => __( 'Edit Service Location', 'professionaldirectory' ),
            'update_item'       => __( 'Update Service Location', 'professionaldirectory' ),
            'add_new_item'      => __( 'Add New Service Location', 'professionaldirectory' ),
            'new_item_name'     => __( 'New Service Location Name', 'professionaldirectory' ),
            'menu_name'         => __( 'Service Location', 'professionaldirectory' ),
        );
    
        $args = array(
            'hierarchical'      => true,
            'labels'            => $labels,
            'show_ui'           => true,
            'show_in_menu'      => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'rewrite'           => array( 'slug' => 'service-location' ),
            'capabilities'      => array(
                'manage_terms' => 'manage_options', // Utiliza a capacidade 'manage_options' para gerenciar termos
                'edit_terms'   => 'manage_options',
                'delete_terms' => 'manage_options',
                'assign_terms' => 'edit_professional_services', // Capacidade para atribuir termos
            ),
        );

        register_taxonomy('service_location', 'professional_service', $args);
    }

    /**
     * Inicializa as funções de registro de taxonomia.
     */
    public static function init() {
        self::register_service_type_taxonomy();
        self::register_service_location_taxonomy();
    }
}

// Hook para iniciar a classe e registrar as taxonomias
add_action('init', ['PDR_Taxonomies', 'init']);

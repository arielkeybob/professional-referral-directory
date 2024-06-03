<?php
/**
 * Classe para gerenciar as taxonomias do ReferralHub.
 */

 defined('ABSPATH') or die('No script kiddies please!');

class RHB_Taxonomies {
    /**
     * Registra a taxonomia Tipo de Serviço.
     */
    public static function register_service_type_taxonomy() {
        $labels = array(
            'name'              => _x( 'Service Types', 'taxonomy general name', 'referralhub' ),
            'singular_name'     => _x( 'Service Type', 'taxonomy singular name', 'referralhub' ),
            'inquiring_items'      => __( 'Service Types', 'referralhub' ),
            'all_items'         => __( 'All Service Types', 'referralhub' ),
            'parent_item'       => __( 'Parent Service Type', 'referralhub' ),
            'parent_item_colon' => __( 'Parent Service Type:', 'referralhub' ),
            'edit_item'         => __( 'Edit Service Type', 'referralhub' ),
            'update_item'       => __( 'Update Service Type', 'referralhub' ),
            'add_new_item'      => __( 'Add New Service Type', 'referralhub' ),
            'new_item_name'     => __( 'New Service Type Name', 'referralhub' ),
            'menu_name'         => __( 'Service Type', 'referralhub' ),
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
                'assign_terms' => 'edit_service_provider_services', // Capacidade para atribuir termos
            ),
        );

        register_taxonomy('service_type', 'rhb_service', $args);
    }

    /**
     * Registra a taxonomia Localização do Serviço.
     */
    public static function register_service_location_taxonomy() { 
        $labels = array(
            'name'              => _x( 'Service Locations', 'taxonomy general name', 'referralhub' ),
            'singular_name'     => _x( 'Service Location', 'taxonomy singular name', 'referralhub' ),
            'inquiring_items'      => __( 'Inquiry Service Locations', 'referralhub' ),
            'all_items'         => __( 'All Service Locations', 'referralhub' ),
            'parent_item'       => __( 'Parent Service Location', 'referralhub' ),
            'parent_item_colon' => __( 'Parent Service Location:', 'referralhub' ),
            'edit_item'         => __( 'Edit Service Location', 'referralhub' ),
            'update_item'       => __( 'Update Service Location', 'referralhub' ),
            'add_new_item'      => __( 'Add New Service Location', 'referralhub' ),
            'new_item_name'     => __( 'New Service Location Name', 'referralhub' ),
            'menu_name'         => __( 'Service Location', 'referralhub' ),
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
                'assign_terms' => 'edit_service_provider_services', // Capacidade para atribuir termos
            ),
        );

        register_taxonomy('service_location', 'rhb_service', $args);
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
add_action('init', ['RHB_Taxonomies', 'init']);

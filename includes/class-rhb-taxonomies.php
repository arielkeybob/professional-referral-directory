<?php
defined('ABSPATH') or die('No script kiddies please!');

class RHB_Taxonomies {

    public static function register_service_type_taxonomy() {
        $labels = array(
            'name'              => _x('Service Types', 'taxonomy general name', 'referralhub'),
            'singular_name'     => _x('Service Type', 'taxonomy singular name', 'referralhub'),
            'search_items'      => __('Search Service Types', 'referralhub'),
            'all_items'         => __('All Service Types', 'referralhub'),
            'parent_item'       => __('Parent Service Type', 'referralhub'),
            'parent_item_colon' => __('Parent Service Type:', 'referralhub'),
            'edit_item'         => __('Edit Service Type', 'referralhub'),
            'update_item'       => __('Update Service Type', 'referralhub'),
            'add_new_item'      => __('Add New Service Type', 'referralhub'),
            'new_item_name'     => __('New Service Type Name', 'referralhub'),
            'menu_name'         => __('Service Type', 'referralhub'),
        );

        $args = array(
            'hierarchical'      => true,
            'labels'            => $labels,
            'show_ui'           => true,
            'show_in_menu'      => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'rewrite'           => array('slug' => 'service-type'),
            'capabilities'      => array(
                'manage_terms' => 'manage_categories', // Apenas administradores
                'edit_terms'   => 'manage_categories', // Apenas administradores
                'delete_terms' => 'manage_categories', // Apenas administradores
                'assign_terms' => 'edit_service_provider_services', // Service providers podem atribuir
            ),
        );

        register_taxonomy('service_type', 'rhb_service', $args);
    }

    public static function register_service_location_taxonomy() {
        $labels = array(
            'name'              => _x('Service Locations', 'taxonomy general name', 'referralhub'),
            'singular_name'     => _x('Service Location', 'taxonomy singular name', 'referralhub'),
            'search_items'      => __('Search Service Locations', 'referralhub'),
            'all_items'         => __('All Service Locations', 'referralhub'),
            'parent_item'       => __('Parent Service Location', 'referralhub'),
            'parent_item_colon' => __('Parent Service Location:', 'referralhub'),
            'edit_item'         => __('Edit Service Location', 'referralhub'),
            'update_item'       => __('Update Service Location', 'referralhub'),
            'add_new_item'      => __('Add New Service Location', 'referralhub'),
            'new_item_name'     => __('New Service Location Name', 'referralhub'),
            'menu_name'         => __('Service Location', 'referralhub'),
        );

        $args = array(
            'hierarchical'      => true,
            'labels'            => $labels,
            'show_ui'           => true,
            'show_in_menu'      => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'rewrite'           => array('slug' => 'service-location'),
            'capabilities'      => array(
                'manage_terms' => 'manage_categories', // Apenas administradores
                'edit_terms'   => 'manage_categories', // Apenas administradores
                'delete_terms' => 'manage_categories', // Apenas administradores
                'assign_terms' => 'edit_service_provider_services', // Service providers podem atribuir
            ),
        );

        register_taxonomy('service_location', 'rhb_service', $args);
    }

    public static function init() {
        self::register_service_type_taxonomy();
        self::register_service_location_taxonomy();
    }
}

add_action('init', ['RHB_Taxonomies', 'init']);
?>
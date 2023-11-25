<?php
// Se este arquivo for chamado diretamente, aborte.
if ( ! defined( 'WPINC' ) ) {
    die;
}

class ProfessionalDirectory_CPT {

    public static function register_service_cpt() {
        $labels = [
            'name'                  => _x('Services', 'Post type general name', 'professionaldirectory'),
            'singular_name'         => _x('Service', 'Post type singular name', 'professionaldirectory'),
            'menu_name'             => _x('Services', 'Admin Menu text', 'professionaldirectory'),
            'name_admin_bar'        => _x('Service', 'Add New on Toolbar', 'professionaldirectory'),
            'add_new'               => __('Add New', 'professionaldirectory'),
            'add_new_item'          => __('Add New Service', 'professionaldirectory'),
            'new_item'              => __('New Service', 'professionaldirectory'),
            'edit_item'             => __('Edit Service', 'professionaldirectory'),
            'view_item'             => __('View Service', 'professionaldirectory'),
            'all_items'             => __('All Services', 'professionaldirectory'),
            'search_items'          => __('Search Services', 'professionaldirectory'),
            'parent_item_colon'     => __('Parent Services:', 'professionaldirectory'),
            'not_found'             => __('No services found.', 'professionaldirectory'),
            'not_found_in_trash'    => __('No services found in Trash.', 'professionaldirectory'),
            'featured_image'        => _x('Service Cover Image', 'Overrides the “Featured Image” phrase for this post type. Added in 4.3', 'professionaldirectory'),
            'set_featured_image'    => _x('Set cover image', 'Overrides the “Set featured image” phrase for this post type. Added in 4.3', 'professionaldirectory'),
            'remove_featured_image' => _x('Remove cover image', 'Overrides the “Remove featured image” phrase for this post type. Added in 4.3', 'professionaldirectory'),
            'use_featured_image'    => _x('Use as cover image', 'Overrides the “Use as featured image” phrase for this post type. Added in 4.3', 'professionaldirectory'),
            'archives'              => _x('Service archives', 'The post type archive label used in nav menus. Default “Post Archives”. Added in 4.4', 'professionaldirectory'),
            'insert_into_item'      => _x('Insert into service', 'Overrides the “Insert into post”/“Insert into page” phrase (used when inserting media into a post). Added in 4.4', 'professionaldirectory'),
            'uploaded_to_this_item' => _x('Uploaded to this service', 'Overrides the “Uploaded to this post”/“Uploaded to this page” phrase (used when viewing media attached to a post). Added in 4.4', 'professionaldirectory'),
            'filter_items_list'     => _x('Filter services list', 'Screen reader text for the filter links heading on the post type listing screen. Default “Filter posts list”/“Filter pages list”. Added in 4.4', 'professionaldirectory'),
            'items_list_navigation' => _x('Services list navigation', 'Screen reader text for the pagination heading on the post type listing screen. Default “Posts list navigation”/“Pages list navigation”. Added in 4.4', 'professionaldirectory'),
            'items_list'            => _x('Services list', 'Screen reader text for the items list heading on the post type listing screen. Default “Posts list”/“Pages list”. Added in 4.4', 'professionaldirectory'),
        ];

        $args = [
            'labels'             => $labels,
            'public'             => true,
            'publicly_queryable' => true,
            'show_ui'            => true,
            'show_in_menu'       => false,
            'query_var'          => true,
            'rewrite'            => ['slug' => 'service'],
            'capability_type'    => 'professional_service',
            'capabilities' => array(
                'publish_posts'       => 'publish_professional_services',
                'edit_posts'          => 'edit_professional_services',
                'edit_others_posts'   => 'edit_others_professional_services',
                'delete_posts'        => 'delete_professional_services',
                'delete_others_posts' => 'delete_others_professional_services',
                'read_private_posts'  => 'read_private_professional_services',
                'edit_post'           => 'edit_professional_service',
                'delete_post'         => 'delete_professional_service',
                'read_post'           => 'read_professional_service',
            ),
            'map_meta_cap'        => true,
            'has_archive'         => true,
            'hierarchical'        => false,
            'menu_position'       => null,
            'supports'            => ['title', 'editor', 'author', 'thumbnail'],
        ];

        register_post_type('professional_service', $args);
    }

    public static function set_service_capabilities() {
        $admin = get_role('administrator');
        $admin_capabilities = array(
            'publish_professional_services',
            'edit_professional_services',
            'edit_others_professional_services',
            'delete_professional_services',
            'delete_others_professional_services',
            'read_private_professional_services',
            'edit_professional_service',
            'delete_professional_service',
            'read_professional_service',
        );

        foreach ($admin_capabilities as $cap) {
            $admin->add_cap($cap);
        }

        $professional = get_role('professional');
        $professional_capabilities = array(
            'publish_professional_services',
            'edit_professional_services',
            'delete_professional_services',
            'read_professional_service',
        );

        foreach ($professional_capabilities as $cap) {
            $professional->add_cap($cap);
        }

        // Removendo capacidades não desejadas para o papel 'professional'
        $professional->remove_cap('edit_others_professional_services');
        $professional->remove_cap('delete_others_professional_services');
        $professional->remove_cap('read_private_professional_services');
    }

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
            'show_in_menu'      => false,
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
    
        register_taxonomy( 'service_type', array( 'professional_service' ), $args );
    }
}

// Registrar o Custom Post Type e a Taxonomia no hook 'init'
add_action('init', [ 'ProfessionalDirectory_CPT', 'register_service_cpt' ]);
add_action('init', [ 'ProfessionalDirectory_CPT', 'register_service_type_taxonomy' ]);

<?php
defined('ABSPATH') or die('No script kiddies please!');

class RHB_CPT {

    public static function register_service_cpt() {
        $labels = [
            'name'                  => _x('Services', 'Post type general name', 'referralhub'),
            'singular_name'         => _x('Service', 'Post type singular name', 'referralhub'),
            'menu_name'             => _x('Services', 'Admin Menu text', 'referralhub'),
            'name_admin_bar'        => _x('Service', 'Add New on Toolbar', 'referralhub'),
            'add_new'               => __('Add New', 'referralhub'),
            'add_new_item'          => __('Add New Service', 'referralhub'),
            'new_item'              => __('New Service', 'referralhub'),
            'edit_item'             => __('Edit Service', 'referralhub'),
            'view_item'             => __('View Service', 'referralhub'),
            'all_items'             => __('All Services', 'referralhub'),
            'inquiring_items'       => __('Inquiry Services', 'referralhub'),
            'parent_item_colon'     => __('Parent Services:', 'referralhub'),
            'not_found'             => __('No services found.', 'referralhub'),
            'not_found_in_trash'    => __('No services found in Trash.', 'referralhub'),
            'featured_image'        => _x('Service Cover Image', 'Overrides the “Featured Image” phrase for this post type. Added in 4.3', 'referralhub'),
            'set_featured_image'    => _x('Set cover image', 'Overrides the “Set featured image” phrase for this post type. Added in 4.3', 'referralhub'),
            'remove_featured_image' => _x('Remove cover image', 'Overrides the “Remove featured image” phrase for this post type. Added in 4.3', 'referralhub'),
            'use_featured_image'    => _x('Use as cover image', 'Overrides the “Use as featured image” phrase for this post type. Added in 4.3', 'referralhub'),
            'archives'              => _x('Service archives', 'The post type archive label used in nav menus. Default “Post Archives”. Added in 4.4', 'referralhub'),
            'insert_into_item'      => _x('Insert into service', 'Overrides the “Insert into post”/“Insert into page” phrase (used when inserting media into a post). Added in 4.4', 'referralhub'),
            'uploaded_to_this_item' => _x('Uploaded to this service', 'Overrides the “Uploaded to this post”/“Uploaded to this page” phrase (used when viewing media attached to a post). Added in 4.4', 'referralhub'),
            'filter_items_list'     => _x('Filter services list', 'Screen reader text for the filter links heading on the post type listing screen. Default “Filter posts list”/“Filter pages list”. Added in 4.4', 'referralhub'),
            'items_list_navigation' => _x('Services list navigation', 'Screen reader text for the pagination heading on the post type listing screen. Default “Posts list navigation”/“Pages list navigation”. Added in 4.4', 'referralhub'),
            'items_list'            => _x('Services list', 'Screen reader text for the items list heading on the post type listing screen. Default “Posts list”/“Pages list”. Added in 4.4', 'referralhub'),
        ];

        $args = [
            'labels'             => $labels,
            'public'             => true,
            'publicly_queryable' => true,
            'show_ui'            => true,
            'show_in_menu'       => true,
            'query_var'          => true,
            'rewrite'            => ['slug' => 'service'],
            'capability_type'    => ['rhb_service', 'rhb_services'],
            'capabilities'       => array(
                'publish_posts'       => 'publish_rhb_services',
                'edit_posts'          => 'edit_rhb_services',
                'edit_others_posts'   => 'edit_others_rhb_services',
                'delete_posts'        => 'delete_rhb_services',
                'delete_others_posts' => 'delete_others_rhb_services',
                'read_private_posts'  => 'read_private_rhb_services',
                'edit_post'           => 'edit_rhb_service',
                'delete_post'         => 'delete_rhb_service',
                'read_post'           => 'read_rhb_service',
            ),
            'map_meta_cap'        => true,
            'has_archive'         => true,
            'hierarchical'        => false,
            'menu_position'       => null,
            'supports'            => ['title', 'editor', 'author', 'thumbnail'],
            'menu_icon'           => 'dashicons-portfolio',
        ];

        register_post_type('rhb_service', $args);
    }

    public static function add_admin_capabilities() {
        // Obtém o objeto do papel do administrador
        $admin_role = get_role('administrator');

        // Verifica se o papel existe antes de tentar adicionar capacidades
        if ($admin_role) {
            // Capacidades para o tipo de postagem 'rhb_service'
            $caps = [
                'edit_rhb_service',
                'read_rhb_service',
                'delete_rhb_service',
                'edit_rhb_services',
                'edit_others_rhb_services',
                'publish_rhb_services',
                'read_private_rhb_services',
                'delete_rhb_services',
                'delete_private_rhb_services',
                'delete_published_rhb_services',
                'delete_others_rhb_services',
                'edit_private_rhb_services',
                'edit_published_rhb_services',
                'create_rhb_services'
            ];

            foreach ($caps as $cap) {
                // Adiciona a capacidade se o administrador ainda não a possuir
                if (!$admin_role->has_cap($cap)) {
                    $admin_role->add_cap($cap);
                }
            }
        }
    }

    public static function set_service_capabilities() {
        $service_provider = get_role('service_provider');
        if ($service_provider) {
            $service_provider_capabilities = [
                'publish_rhb_services',
                'edit_rhb_services',
                'edit_rhb_service',
                'edit_published_rhb_services',
                'create_rhb_services',
                'read_rhb_service',
                'delete_rhb_service'
                // Adicione mais capacidades conforme necessário
            ];

            foreach ($service_provider_capabilities as $cap) {
                $service_provider->add_cap($cap);
            }

            // Removendo capacidades não desejadas para o papel 'service_provider'
            $service_provider->remove_cap('edit_others_rhb_services');
            $service_provider->remove_cap('delete_others_rhb_services');
            $service_provider->remove_cap('read_private_rhb_services');
        }
    }
}

// Registrar o Custom Post Type e a Taxonomia no hook 'init'
add_action('init', ['RHB_CPT', 'register_service_cpt']);
add_action('init', ['RHB_CPT', 'add_admin_capabilities']);
add_action('init', ['RHB_CPT', 'set_service_capabilities']);

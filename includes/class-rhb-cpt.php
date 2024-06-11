<?php
defined('ABSPATH') or die('No script kiddies please!');

class RHB_CPT {

    public static function register_service_cpt() {
        $labels = [
            'name'                  => _x('Services', 'Post type general name', 'referralhub'),
            'singular_name'         => _x('Service', 'Post type singular name', 'referralhub'),
            'menu_name'             => _x('Services', 'Admin Menu text', 'referralhub'),
            'add_new'               => __('Add New', 'referralhub'),
            'add_new_item'          => __('Add New Service', 'referralhub'),
            'edit_item'             => __('Edit Service', 'referralhub'),
            'view_item'             => __('View Service', 'referralhub'),
            'all_items'             => __('All Services', 'referralhub'),
            'search_items'          => __('Search Services', 'referralhub'),
            'not_found'             => __('No services found.', 'referralhub'),
            'not_found_in_trash'    => __('No services found in Trash.', 'referralhub'),
        ];

        $args = [
            'labels'             => $labels,
            'public'             => true,
            'show_ui'            => true,
            'show_in_menu'       => true,
            'query_var'          => true,
            'rewrite'            => ['slug' => 'service'],
            'capability_type'    => 'service_provider_service',
            'capabilities'       => [
                'edit_post'           => 'edit_service_provider_service',
                'read_post'           => 'read_service_provider_service',
                'delete_post'         => 'delete_service_provider_service',
                'edit_posts'          => 'edit_service_provider_services',
                'edit_others_posts'   => 'edit_others_service_provider_services',
                'publish_posts'       => 'publish_service_provider_services',
                'read_private_posts'  => 'read_private_service_provider_services',
                'delete_posts'        => 'delete_service_provider_services',
                'delete_published_posts' => 'delete_published_service_provider_services',
                'delete_others_posts' => 'delete_others_service_provider_services',
            ],
            'map_meta_cap'        => true,
            'has_archive'         => true,
            'hierarchical'        => false,
            'supports'            => ['title', 'editor', 'author', 'thumbnail'],
            'menu_icon'           => 'dashicons-portfolio',
        ];

        register_post_type('rhb_service', $args);
    }

    public static function add_admin_capabilities() {
        $admin_role = get_role('administrator');

        if ($admin_role) {
            $caps = [
                'edit_service_provider_service',
                'read_service_provider_service',
                'delete_service_provider_service',
                'edit_service_provider_services',
                'edit_others_service_provider_services',
                'publish_service_provider_services',
                'read_private_service_provider_services',
                'delete_service_provider_services',
                'delete_published_service_provider_services',
                'delete_others_service_provider_services',
                'edit_private_service_provider_services',
                'edit_published_service_provider_services',
                'create_service_provider_services'
            ];

            foreach ($caps as $cap) {
                if (!$admin_role->has_cap($cap)) {
                    $admin_role->add_cap($cap);
                }
            }
        }
    }

    public static function set_service_capabilities() {
        $service_provider = get_role('service_provider');
        if ($service_provider) {
            $caps = [
                'edit_service_provider_service',
                'read_service_provider_service',
                'delete_service_provider_service',
                'edit_service_provider_services',
                'publish_service_provider_services',
                'edit_published_service_provider_services',
                'delete_service_provider_services',
                'delete_published_service_provider_services',
            ];

            foreach ($caps as $cap) {
                $service_provider->add_cap($cap);
            }

            $service_provider->remove_cap('edit_others_service_provider_services');
            $service_provider->remove_cap('delete_others_service_provider_services');
            $service_provider->remove_cap('read_private_service_provider_services');
        }
    }

    public static function register_hooks() {
        add_action('init', [__CLASS__, 'register_service_cpt']);
        add_action('init', [__CLASS__, 'add_admin_capabilities']);
        add_action('init', [__CLASS__, 'set_service_capabilities']);
    }
}

RHB_CPT::register_hooks();

<?php
    // Se este arquivo for chamado diretamente, aborte.
    if ( ! defined( 'WPINC' ) ) {
        die;
    }

    class PDR_CPT {

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
                'show_in_menu'       => true,
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
                'menu_icon'          => 'dashicons-portfolio',
            ];

            register_post_type('professional_service', $args);
        }

        public static function add_admin_capabilities() {
            // Obtém o objeto do papel do administrador
            $admin_role = get_role('administrator');
    
            // Verifica se o papel existe antes de tentar adicionar capacidades
            if ($admin_role) {
                // Capacidades para o tipo de postagem 'professional_service'
                $caps = [
                    'edit_professional_service',
                    'read_professional_service',
                    'delete_professional_service',
                    'edit_professional_services',
                    'edit_others_professional_services',
                    'publish_professional_services',
                    'read_private_professional_services',
                    'delete_professional_services',
                    'delete_private_professional_services',
                    'delete_published_professional_services',
                    'delete_others_professional_services',
                    'edit_private_professional_services',
                    'edit_published_professional_services',
                    'create_professional_services'
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
            $professional = get_role('professional');
            if ($professional) {
                $professional_capabilities = [
                    'publish_professional_services',
                    'edit_professional_services',
                    'edit_professional_service',
                    'edit_published_professional_services',
                    // Adicione mais capacidades conforme necessário
                ];

            foreach ($professional_capabilities as $cap) {
                $professional->add_cap($cap);
            }

            // Removendo capacidades não desejadas para o papel 'professional'
            $professional->remove_cap('edit_others_professional_services');
            $professional->remove_cap('delete_others_professional_services');
            $professional->remove_cap('read_private_professional_services');
        }
        
    }
}

    // Registrar o Custom Post Type e a Taxonomia no hook 'init'
    add_action('init', ['PDR_CPT', 'register_service_cpt']);
add_action('init', ['PDR_CPT', 'add_admin_capabilities']);
add_action('init', ['PDR_CPT', 'set_service_capabilities']);
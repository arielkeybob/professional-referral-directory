<?php
// Se este arquivo for chamado diretamente, aborte.
if ( ! defined( 'WPINC' ) ) {
    die;
}

class ProfessionalDirectory_CPT {
    public static function register_service_cpt() {
        error_log('Registrando o CPT'); // Vai logar no arquivo de debug.log se estiver sendo chamado.

        $labels = [
            'name'               => _x( 'Professional Services', 'Post type general name', 'professionaldirectory' ),
            'singular_name'      => _x( 'Professional Service', 'Post type singular name', 'professionaldirectory' ),
            'menu_name'          => _x( 'Professional Services', 'Admin Menu text', 'professionaldirectory' ),
            'name_admin_bar'     => _x( 'Professional Service', 'Add New on Toolbar', 'professionaldirectory' ),
            // ... Adicione outras labels conforme necessário
        ];

        $args = [
            'labels'             => $labels,
            'public'             => true,
            'publicly_queryable' => true,
            'show_ui'            => true,
            'show_in_menu'       => true,
            'query_var'          => true,
            'rewrite'            => ['slug' => 'professional-service'],
            'capability_type'    => ['professional_service', 'professional_services'],
            'map_meta_cap'       => true,
            'has_archive'        => true,
            'hierarchical'       => false,
            'menu_position'      => null,
            'supports'           => ['title', 'editor', 'author'],
            // ... Adicione outros argumentos conforme necessário
        ];

        register_post_type( 'professional_service', $args );
    }

    public static function set_service_capabilities() {
        $roles = ['professional', 'administrator']; // Inclua administradores.

        foreach ($roles as $the_role) {
            $role = get_role( $the_role );

            if ( ! is_null( $role ) ) {
                $capabilities = [
                    'read',
                    'read_professional_service',
                    'read_private_professional_services',
                    'edit_professional_service',
                    'edit_professional_services',
                    'edit_others_professional_services',
                    'edit_published_professional_services',
                    'publish_professional_services',
                    'delete_others_professional_services',
                    'delete_private_professional_services',
                    'delete_published_professional_services',
                    // 'delete_professional_service' é intencionalmente omitido
                ];

                foreach ( $capabilities as $cap ) {
                    $role->add_cap( $cap );
                }
            }
        }
    }
}


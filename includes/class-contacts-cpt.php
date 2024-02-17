<?php
// Verifica se o arquivo foi chamado diretamente.
if (!defined('WPINC')) {
    die;
}

class Contatos_CPT {
    public function __construct() {
        add_action('init', array($this, 'registrar_cpt_contato'));
    }

    /**
     * Registra o Custom Post Type para Contatos.
     */
    public function registrar_cpt_contato() {
        $labels = array(
            'name'                  => _x('Contatos', 'Post Type General Name', 'professionaldirectory'),
            'singular_name'         => _x('Contato', 'Post Type Singular Name', 'professionaldirectory'),
            'menu_name'             => __('Contatos', 'professionaldirectory'),
            'name_admin_bar'        => __('Contato', 'professionaldirectory'),
            'archives'              => __('Item Archives', 'professionaldirectory'),
            'attributes'            => __('Item Attributes', 'professionaldirectory'),
            'parent_item_colon'     => __('Parent Item:', 'professionaldirectory'),
            'all_items'             => __('Todos Contatos', 'professionaldirectory'),
            'add_new_item'          => __('Adicionar Novo Contato', 'professionaldirectory'),
            'add_new'               => __('Adicionar Novo', 'professionaldirectory'),
            'new_item'              => __('Novo Contato', 'professionaldirectory'),
            'edit_item'             => __('Editar Contato', 'professionaldirectory'),
            'update_item'           => __('Atualizar Contato', 'professionaldirectory'),
            'view_item'             => __('Ver Contato', 'professionaldirectory'),
            'view_items'            => __('Ver Contatos', 'professionaldirectory'),
            'search_items'          => __('Buscar Contato', 'professionaldirectory'),
            'not_found'             => __('Não encontrado', 'professionaldirectory'),
            'not_found_in_trash'    => __('Não encontrado na lixeira', 'professionaldirectory'),
            'featured_image'        => __('Imagem Destacada', 'professionaldirectory'),
            'set_featured_image'    => __('Definir imagem destacada', 'professionaldirectory'),
            'remove_featured_image' => __('Remover imagem destacada', 'professionaldirectory'),
            'use_featured_image'    => __('Usar como imagem destacada', 'professionaldirectory'),
            'insert_into_item'      => __('Inserir no contato', 'professionaldirectory'),
            'uploaded_to_this_item' => __('Carregado para este contato', 'professionaldirectory'),
            'items_list'            => __('Lista de contatos', 'professionaldirectory'),
            'items_list_navigation' => __('Navegação da lista de contatos', 'professionaldirectory'),
            'filter_items_list'     => __('Filtrar lista de contatos', 'professionaldirectory'),
        );
        $args = array(
            'label'                 => __('Contato', 'professionaldirectory'),
            'description'           => __('Post Type para gerenciar contatos', 'professionaldirectory'),
            'labels'                => $labels,
            'supports'              => array('title', 'editor', 'custom-fields'),
            'hierarchical'          => false,
            'public'                => true,
            'show_ui'               => true,
            'show_in_menu'          => true,
            'menu_position'         => 5,
            'show_in_admin_bar'     => true,
            'show_in_nav_menus'     => true,
            'can_export'            => true,
            'has_archive'           => true,
            'exclude_from_search'   => false,
            'publicly_queryable'    => true,
            'capability_type'       => 'post',
            'show_in_rest'          => true,
        );
        register_post_type('contato', $args);
    }
}

// Instancia a classe para garantir que o CPT seja registrado.
new Contatos_CPT();

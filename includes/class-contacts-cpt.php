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
            'name'                  => _x('Contatos', 'Post Type General Name', 'seu-plugin'),
            'singular_name'         => _x('Contato', 'Post Type Singular Name', 'seu-plugin'),
            'menu_name'             => __('Contatos', 'seu-plugin'),
            'name_admin_bar'        => __('Contato', 'seu-plugin'),
            'archives'              => __('Item Archives', 'seu-plugin'),
            'attributes'            => __('Item Attributes', 'seu-plugin'),
            'parent_item_colon'     => __('Parent Item:', 'seu-plugin'),
            'all_items'             => __('Todos Contatos', 'seu-plugin'),
            'add_new_item'          => __('Adicionar Novo Contato', 'seu-plugin'),
            'add_new'               => __('Adicionar Novo', 'seu-plugin'),
            'new_item'              => __('Novo Contato', 'seu-plugin'),
            'edit_item'             => __('Editar Contato', 'seu-plugin'),
            'update_item'           => __('Atualizar Contato', 'seu-plugin'),
            'view_item'             => __('Ver Contato', 'seu-plugin'),
            'view_items'            => __('Ver Contatos', 'seu-plugin'),
            'search_items'          => __('Buscar Contato', 'seu-plugin'),
            'not_found'             => __('Não encontrado', 'seu-plugin'),
            'not_found_in_trash'    => __('Não encontrado na lixeira', 'seu-plugin'),
            'featured_image'        => __('Imagem Destacada', 'seu-plugin'),
            'set_featured_image'    => __('Definir imagem destacada', 'seu-plugin'),
            'remove_featured_image' => __('Remover imagem destacada', 'seu-plugin'),
            'use_featured_image'    => __('Usar como imagem destacada', 'seu-plugin'),
            'insert_into_item'      => __('Inserir no contato', 'seu-plugin'),
            'uploaded_to_this_item' => __('Carregado para este contato', 'seu-plugin'),
            'items_list'            => __('Lista de contatos', 'seu-plugin'),
            'items_list_navigation' => __('Navegação da lista de contatos', 'seu-plugin'),
            'filter_items_list'     => __('Filtrar lista de contatos', 'seu-plugin'),
        );
        $args = array(
            'label'                 => __('Contato', 'seu-plugin'),
            'description'           => __('Post Type para gerenciar contatos', 'seu-plugin'),
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

<?php
if (!defined('WPINC')) {
    die;
}

class Contatos_Admin_Page {
    public function __construct() {
        add_action('admin_menu', array($this, 'adicionar_menu_contatos'));
        add_action('init', array($this, 'pdr_add_contacts_capability'));
    }

    /**
     * Adiciona capacidade para o tipo de usuário 'professional' ver os contatos.
     */
    public function pdr_add_contacts_capability() {
        $role = get_role('professional');
        if ($role) {
            $role->add_cap('view_pdr_contacts'); // Adiciona capacidade para ver os contatos.
        }
    }

    /**
     * Adiciona um menu de contatos ao painel de administração.
     */
    public function adicionar_menu_contatos() {
        add_submenu_page(
            'edit.php?post_type=professional_service', // Ajuste conforme necessário.
            'Dashboard do Professional',
            'Dashboard',
            'view_pdr_contacts', // Use a capacidade que você adicionou acima.
            'pdr-dashboard',
            array($this, 'pdr_contacts_page_content') // Referência ao método corrigido.
        );
    }

    /**
     * Renderiza a página de gerenciamento de contatos no painel de administração.
     */
    public function pdr_contacts_page_content() {
        if (!current_user_can('view_pdr_contacts')) {
            wp_die(__('Você não tem permissão para acessar esta página.', 'professionaldirectory'));
        }

        echo '<div class="wrap">';
        echo '<h1>' . esc_html__('Gerenciamento de Contatos', 'professionaldirectory') . '</h1>';
        // Implemente a lógica para mostrar os contatos e suas ações aqui.
        echo '</div>';
    }
}

// Inicializa a classe para adicionar a funcionalidade ao WordPress.
new Contatos_Admin_Page();

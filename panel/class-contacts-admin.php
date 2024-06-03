<?php
    defined('ABSPATH') or die('No script kiddies please!');

// Inclui a classe ContactService para gerenciar as consultas de contato.
require_once dirname(__DIR__) .  '\panel\class-contact-service.php';


class Contatos_Admin_Page {
    protected $contactService;

    public function __construct() {
        $this->contactService = new ContactService();
        // Hook para adicionar o conteúdo da página no submenu correto, adicionado em panel-menus.php.
        // Este passo é mencionado, mas não implementado aqui. Deve ser feito no arquivo panel-menus.php.
    }

    public function render() {
        // Verifica se o usuário atual tem permissão para visualizar esta página.
        if (!current_user_can('view_rhb_contacts')) {
            wp_die(__('Você não tem permissão para acessar esta página.', 'referralhub'));
        }

        $current_user_id = get_current_user_id();
        
        // Usa a ContactService para buscar contatos do usuário atual.
        $contacts = $this->contactService->getContactsByAuthor($current_user_id);

        // Início da renderização da página de gerenciamento de contatos.
        echo '<div class="wrap">';
        echo '<h1>' . esc_html__('Gerenciamento de Contatos', 'referralhub') . '</h1>';

        // Verifica se foram encontrados contatos e os exibe em uma tabela.
        if (!empty($contacts)) {
            echo '<table class="wp-list-table widefat fixed striped">';
            echo '<thead>';
            echo '<tr><th>' . esc_html__('Name', 'referralhub') . '</th><th>' . esc_html__('Email', 'referralhub') . '</th><th>' . esc_html__('Ações', 'referralhub') . '</th></tr>';
            echo '</thead>';
            echo '<tbody>';

            foreach ($contacts as $contact) {
                $display_name = (!empty($contact['custom_name'])) ? $contact['custom_name'] : $contact['default_name'];
                
                $details_url = wp_nonce_url(
                    add_query_arg(['page' => 'rhb-contact-details', 'contact_id' => $contact['contact_id']], admin_url('admin.php')),
                    'view_contact_details_' . $contact['contact_id'],
                    'contact_nonce'
                );

                echo '<tr>';
                echo '<td>' . esc_html($display_name) . '</td>';
                echo '<td>' . esc_html($contact['email']) . '</td>';
                echo '<td><a href="' . esc_url($details_url) . '" class="button-secondary">' . __('Ver Detalhes', 'referralhub') . '</a></td>';
                echo '</tr>';
            }

            echo '</tbody>';
            echo '</table>';
        } else {
            echo '<p>' . esc_html__('Nenhum contato encontrado.', 'referralhub') . '</p>';
        }

        echo '</div>'; // Fim da div wrap.
    }
}

// A instanciação da classe e a adição ao menu devem ser feitas em outro lugar, como sugerido em panel-menus.php.

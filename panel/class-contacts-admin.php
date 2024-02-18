<?php
if (!defined('WPINC')) {
    die;
}

class Contatos_Admin_Page {
    public function __construct() {
        // Hook para adicionar o conteúdo da página no submenu correto, adicionado em panel-menus.php
    }

    /**
     * Renderiza a página de gerenciamento de contatos no painel de administração.
     */
    public function render() {
        if (!current_user_can('view_pdr_contacts')) {
            wp_die(__('Você não tem permissão para acessar esta página.', 'professionaldirectory'));
        }

        global $wpdb;

        // Obtém o ID do usuário atual para buscar os contatos relacionados.
        $current_user_id = get_current_user_id();

        // Consulta que une as tabelas de contatos e a relação contato-autor para buscar contatos relacionados ao autor.
        $query = $wpdb->prepare(
            "SELECT c.contact_id, c.email, c.default_name
            FROM {$wpdb->prefix}pdr_contact_author_relation car
            JOIN {$wpdb->prefix}pdr_contacts c ON car.contact_id = c.contact_id
            WHERE car.author_id = %d
            GROUP BY c.contact_id",
            $current_user_id
        );

        $contacts = $wpdb->get_results($query, ARRAY_A);

        echo '<div class="wrap">';
        echo '<h1>' . esc_html__('Gerenciamento de Contatos', 'professionaldirectory') . '</h1>';

        // Verifica se existem contatos e exibe em uma tabela.
        if (!empty($contacts)) {
            echo '<table class="wp-list-table widefat fixed striped">';
            echo '<thead>';
            echo '<tr><th>' . esc_html__('Nome', 'professionaldirectory') . '</th><th>' . esc_html__('Email', 'professionaldirectory') . '</th></tr>';
            echo '</thead>';
            echo '<tbody>';

            foreach ($contacts as $contact) {
                echo '<tr>';
                echo '<td>' . esc_html($contact['default_name']) . '</td>';
                echo '<td>' . esc_html($contact['email']) . '</td>';
                echo '</tr>';
            }

            echo '</tbody>';
            echo '</table>';
        } else {
            echo '<p>' . esc_html__('Nenhum contato encontrado.', 'professionaldirectory') . '</p>';
        }

        echo '</div>';
    }
}

// A instanciação da classe e adição ao menu é feita em panel-menus.php para evitar duplicação de menus.

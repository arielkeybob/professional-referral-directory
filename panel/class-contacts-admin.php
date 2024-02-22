<?php
ob_start();
if (!defined('WPINC')) {
    die;
}

class Contatos_Admin_Page {
    public function __construct() {
        // Hook para adicionar o conteúdo da página no submenu correto, adicionado em panel-menus.php
    }

    public function render() {
        if (!current_user_can('view_pdr_contacts')) {
            wp_die(__('Você não tem permissão para acessar esta página.', 'professionaldirectory'));
        }

        global $wpdb;
        $current_user_id = get_current_user_id();

        $contacts = $wpdb->get_results($wpdb->prepare(
            "SELECT c.contact_id, c.email, c.default_name
            FROM {$wpdb->prefix}pdr_author_contact_relations car
            JOIN {$wpdb->prefix}pdr_contacts c ON car.contact_id = c.contact_id
            WHERE car.author_id = %d
            GROUP BY c.contact_id",
            $current_user_id
        ), ARRAY_A);

        echo '<div class="wrap">';
        echo '<h1>' . esc_html__('Gerenciamento de Contatos', 'professionaldirectory') . '</h1>';

        if (!empty($contacts)) {
            echo '<table class="wp-list-table widefat fixed striped">';
            echo '<thead>';
            echo '<tr><th>' . esc_html__('Nome', 'professionaldirectory') . '</th><th>' . esc_html__('Email', 'professionaldirectory') . '</th><th>' . esc_html__('Ações', 'professionaldirectory') . '</th></tr>';
            echo '</thead>';
            echo '<tbody>';

            foreach ($contacts as $contact) {
                // A linha da tabela agora tem uma coluna extra com um link para a página de detalhes do contato
                $details_url = wp_nonce_url(
                    add_query_arg(['page' => 'pdr-contact-details', 'contact_id' => $contact['contact_id']], admin_url('admin.php')),
                    'view_contact_details_' . $contact['contact_id'],
                    'contact_nonce'
                );                echo '<tr>';
                echo '<td>' . esc_html($contact['default_name']) . '</td>';
                echo '<td>' . esc_html($contact['email']) . '</td>';
                echo '<td><a href="' . $details_url . '" class="button-secondary">' . __('Ver Detalhes', 'professionaldirectory') . '</a></td>';
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
ob_end_flush();
// A instanciação da classe e a adição ao menu são feitas em panel-menus.php para evitar duplicação.

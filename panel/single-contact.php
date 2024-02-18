<?php
// Verifica se este arquivo não está sendo acessado diretamente
if (!defined('WPINC')) {
    die;
}

// Verifica se o usuário atual tem permissão para visualizar a página
if (!current_user_can('view_pdr_contacts') || !isset($_GET['contact_nonce']) || !wp_verify_nonce($_GET['contact_nonce'], 'view_contact_details_' . $_GET['contact_id'])) {
    wp_die(__('Você não tem permissão para acessar esta página.', 'professionaldirectory'));
}


global $wpdb;

// Suponha que o ID do contato é passado via query string como 'contact_id'
$contact_id = isset($_GET['contact_id']) ? intval($_GET['contact_id']) : 0;

if ($contact_id) {
    // Busca as informações do contato
    $contact = $wpdb->get_row($wpdb->prepare(
        "SELECT * FROM {$wpdb->prefix}pdr_contacts WHERE contact_id = %d", 
        $contact_id
    ));

    // Busca as pesquisas associadas ao contato
    $searches = $wpdb->get_results($wpdb->prepare(
        "SELECT s.*, r.status, r.custom_name FROM {$wpdb->prefix}pdr_search_data s
        INNER JOIN {$wpdb->prefix}pdr_contact_author_relation r ON s.contact_id = r.contact_id
        WHERE s.contact_id = %d AND r.author_id = %d",
        $contact_id,
        get_current_user_id()
    ));
    
    // Aqui você incluiria o HTML para exibir as informações do contato e as pesquisas
    // Por exemplo:
    echo '<div class="wrap">';
    echo '<h1>' . esc_html__('Detalhes do Contato', 'professionaldirectory') . '</h1>';

    if ($contact) {
        // Exibe as informações do contato
        echo '<p><strong>Nome:</strong> ' . esc_html($contact->default_name) . '</p>';
        echo '<p><strong>Email:</strong> ' . esc_html($contact->email) . '</p>';

        // Exibe as pesquisas associadas ao contato
        if (!empty($searches)) {
            echo '<h2>' . esc_html__('Pesquisas Associadas', 'professionaldirectory') . '</h2>';
            echo '<ul>';
            foreach ($searches as $search) {
                echo '<li>';
                echo '<strong>Data da Pesquisa:</strong> ' . esc_html($search->search_date) . '<br>';
                echo '<strong>Tipo de Serviço:</strong> ' . esc_html($search->service_type) . '<br>';
                echo '<strong>Status:</strong> ' . esc_html($search->status) . '<br>';
                echo '<strong>Nome Personalizado:</strong> ' . esc_html($search->custom_name);
                echo '</li>';
            }
            echo '</ul>';
        } else {
            echo '<p>' . esc_html__('Este contato não tem pesquisas associadas.', 'professionaldirectory') . '</p>';
        }
    } else {
        echo '<p>' . esc_html__('Contato não encontrado.', 'professionaldirectory') . '</p>';
    }
    echo '</div>';
} else {
    wp_die(__('ID do contato não especificado.', 'professionaldirectory'));
}
?>

<?php
// Certifique-se de que este arquivo não seja acessado diretamente
if (!defined('WPINC')) {
    die;
}

// Presume-se que $contact_id e $wpdb já estão definidos no contexto em que este arquivo é incluído.

// Busca as pesquisas associadas ao contato
// Obtenha o ID do usuário logado
$author_id = get_current_user_id();

// Modifique a consulta para incluir a verificação do author_id
$searches = $wpdb->get_results($wpdb->prepare("SELECT * FROM {$wpdb->prefix}pdr_search_data WHERE contact_id = %d AND author_id = %d", $contact_id, $author_id));


// Exibe as pesquisas associadas
if (!empty($searches)) {
    echo '<h2>' . esc_html__('Pesquisas Associadas', 'professionaldirectory') . '</h2>';
    echo '<ul>';
    foreach ($searches as $search) {
        echo '<li>';
        echo '<strong>Data da Pesquisa:</strong> ' . esc_html($search->search_date) . '<br>';
        echo '<strong>Tipo de Serviço:</strong> ' . esc_html($search->service_type) . '<br>';

        // Formulário para atualizar o status da pesquisa
        echo '<select name="search_status" class="search_status" data-search-id="' . esc_attr($search->id) . '">';
        foreach (['pendente', 'aprovado', 'rejeitado'] as $option) {
            echo '<option value="' . esc_attr($option) . '"' . selected($search->search_status, $option, false) . '>' . esc_html(ucfirst($option)) . '</option>';
        }
        echo '</select>';
        echo '</li>';
    }
    echo '</ul>';
} else {
    echo '<p>' . esc_html__('Este contato não tem pesquisas associadas.', 'professionaldirectory') . '</p>';
}

<?php
if (!defined('WPINC')) {
    die;
}

if (!current_user_can('view_pdr_contacts') || !isset($_GET['contact_nonce']) || !wp_verify_nonce($_GET['contact_nonce'], 'view_contact_details_' . $_GET['contact_id'])) {
    wp_die(__('Você não tem permissão para acessar esta página.', 'professionaldirectory'));
}

global $wpdb;
$contact_id = isset($_GET['contact_id']) ? intval($_GET['contact_id']) : 0;

// Processamento do formulário de atualização de status
if ('POST' === $_SERVER['REQUEST_METHOD'] && isset($_POST['contact_status']) && check_admin_referer('update_contact_status_' . $contact_id)) {
    $new_status = $_POST['contact_status'];
    $wpdb->update(
        "{$wpdb->prefix}pdr_author_contact_relations",
        ['status' => $new_status],
        ['contact_id' => $contact_id, 'author_id' => get_current_user_id()]
    );

    // Redireciona para a mesma página para evitar ressubmissões do formulário e mostra o conteúdo atualizado
    $redirect_url = add_query_arg([
        'page' => 'pdr-contacts',
        'contact_id' => $contact_id,
        'contact_nonce' => wp_create_nonce('view_contact_details_' . $contact_id)
    ], admin_url('admin.php'));
    wp_safe_redirect($redirect_url);
    exit;
}

if ($contact_id) {
    $contact = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$wpdb->prefix}pdr_contacts WHERE contact_id = %d", $contact_id));
    $searches = $wpdb->get_results($wpdb->prepare("SELECT * FROM {$wpdb->prefix}pdr_search_data WHERE contact_id = %d", $contact_id));
    $status = $wpdb->get_var($wpdb->prepare("SELECT status FROM {$wpdb->prefix}pdr_author_contact_relations WHERE contact_id = %d AND author_id = %d", $contact_id, get_current_user_id()));

    echo '<div class="wrap">';
    echo '<h1>' . esc_html__('Detalhes do Contato', 'professionaldirectory') . '</h1>';

    if ($contact) {
        echo '<p><strong>Nome:</strong> ' . esc_html($contact->default_name) . '</p>';
        echo '<p><strong>Email:</strong> ' . esc_html($contact->email) . '</p>';

        // Formulário para atualizar o status
        echo '<form method="post" action="">';
        wp_nonce_field('update_contact_status_' . $contact_id);
        echo '<label for="contact_status">Status:</label>';
        echo '<select name="contact_status" id="contact_status">';
        foreach (['active', 'lead', 'not_interested', 'client'] as $option) {
            echo '<option value="' . esc_attr($option) . '"' . selected($status, $option, false) . '>' . esc_html(ucfirst($option)) . '</option>';
        }
        echo '</select>';
        echo '<input type="submit" value="' . esc_attr__('Update Status', 'professionaldirectory') . '" class="button button-primary">';
        echo '</form>';

        // Exibe as pesquisas associadas
        if (!empty($searches)) {
            echo '<h2>' . esc_html__('Pesquisas Associadas', 'professionaldirectory') . '</h2>';
            echo '<ul>';
            foreach ($searches as $search) {
                echo '<li>';
                echo '<strong>Data da Pesquisa:</strong> ' . esc_html($search->search_date) . '<br>';
                echo '<strong>Tipo de Serviço:</strong> ' . esc_html($search->service_type) . '<br>';
                // Aqui você pode adicionar mais detalhes sobre cada pesquisa, se necessário
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

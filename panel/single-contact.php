<?php
ob_start();
if (!defined('WPINC')) {
    die;
}

// Verificar se a depuração está habilitada
if (WP_DEBUG) {
    @ini_set('log_errors', 'On');
    @ini_set('display_errors', 'Off');
    @ini_set('error_log', WP_CONTENT_DIR . '/debug.log');
}

// Verifica permissões e nonce
if (!current_user_can('view_pdr_contacts') || !isset($_GET['contact_nonce']) || !wp_verify_nonce($_GET['contact_nonce'], 'view_contact_details_' . $_GET['contact_id'])) {
    wp_die(__('Você não tem permissão para acessar esta página.', 'professionaldirectory'));
}

global $wpdb;
$contact_id = isset($_GET['contact_id']) ? intval($_GET['contact_id']) : 0;

// Processamento do formulário de atualização de status e nome customizado
// Processamento do formulário de atualização de status e nome customizado
// Processamento do formulário de atualização de status e nome customizado
if ('POST' === $_SERVER['REQUEST_METHOD']) {
    error_log('Processando POST request.');

    // Atualização do status do contato
    if (isset($_POST['contact_status']) && check_admin_referer('update_contact_status_' . $contact_id)) {
        $new_status = $_POST['contact_status'];
        error_log('Tentando atualizar o status do contato para: ' . $new_status);
        
        $update_result = $wpdb->update(
            "{$wpdb->prefix}pdr_author_contact_relations",
            ['status' => $new_status],
            ['contact_id' => $contact_id, 'author_id' => get_current_user_id()]
        );

        if (false === $update_result) {
            error_log('Erro ao atualizar o status do contato: ' . $wpdb->last_error);
        } else {
            error_log('Status do contato atualizado com sucesso.');
        }
    }

    // Atualização do nome customizado
    if (isset($_POST['custom_name'])) {
        $custom_name = sanitize_text_field($_POST['custom_name']);
        error_log('Tentando atualizar o nome customizado para: ' . $custom_name);

        // Verificação se o custom_name é diferente do default_name
        $default_name = $wpdb->get_var($wpdb->prepare("SELECT default_name FROM {$wpdb->prefix}pdr_contacts WHERE contact_id = %d", $contact_id));
        if ($custom_name !== $default_name) {
            $update_result = $wpdb->update(
                "{$wpdb->prefix}pdr_author_contact_relations",
                ['custom_name' => $custom_name],
                ['contact_id' => $contact_id, 'author_id' => get_current_user_id()]
            );

            if (false === $update_result) {
                error_log('Erro ao atualizar o nome customizado: ' . $wpdb->last_error);
            } else {
                error_log('Nome customizado atualizado com sucesso.');
            }
        }
    }

    // Atualização do status das pesquisas
    // Atualização do status das pesquisas
    if (isset($_POST['search_status']) && isset($_POST['search_id'])) {
        $search_id = intval($_POST['search_id']);
        $search_status = sanitize_text_field($_POST['search_status']);
        error_log('Tentando atualizar o search_status da pesquisa ID ' . $search_id . ' para: ' . $search_status);

        // Verificação de nonce aqui seria ideal
        $update_result = $wpdb->update(
            "{$wpdb->prefix}pdr_search_data",
            ['search_status' => $search_status], // Corrigido para usar o nome correto da coluna
            ['id' => $search_id]
        );

        if (false === $update_result) {
            error_log('Erro ao atualizar o search_status da pesquisa: ' . $wpdb->last_error);
        } else {
            error_log('Search_status da pesquisa atualizado com sucesso.');
        }
    }


    // Redirecionamento após processamento
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
    $status = $wpdb->get_var($wpdb->prepare("SELECT status FROM {$wpdb->prefix}pdr_author_contact_relations WHERE contact_id = %d AND author_id = %d", $contact_id, get_current_user_id()));
    $custom_name = $wpdb->get_var($wpdb->prepare("SELECT custom_name FROM {$wpdb->prefix}pdr_author_contact_relations WHERE contact_id = %d AND author_id = %d", $contact_id, get_current_user_id()));
    $searches = $wpdb->get_results($wpdb->prepare("SELECT s.* FROM {$wpdb->prefix}pdr_search_data WHERE contact_id = %d", $contact_id));

    // Busca as pesquisas associadas ao contato
    $searches = $wpdb->get_results($wpdb->prepare(
        "SELECT s.* FROM {$wpdb->prefix}pdr_search_data s WHERE s.contact_id = %d",
        $contact_id
    ));

    echo '<div class="wrap">';
    echo '<h1>' . esc_html__('Detalhes do Contato', 'professionaldirectory') . '</h1>';

    if ($contact) {
        echo '<p><strong>Nome:</strong> ' . esc_html($contact->default_name) . '</p>';
        echo '<p><strong>Email:</strong> ' . esc_html($contact->email) . '</p>';

        // Formulário para atualizar o status e o nome customizado
        echo '<form method="post" action="">';
        wp_nonce_field('update_contact_status_' . $contact_id);
        echo '<label for="contact_status">Status:</label>';
        echo '<select name="contact_status" id="contact_status">';
        foreach (['active', 'lead', 'not_interested', 'client'] as $option) {
            echo '<option value="' . esc_attr($option) . '"' . selected($status, $option, false) . '>' . esc_html(ucfirst($option)) . '</option>';
        }
        echo '</select>';
        // Campo para editar o nome customizado
        echo '<p><strong>Nome Personalizado:</strong></p>';
        echo '<input type="text" name="custom_name" value="' . esc_attr($custom_name ? $custom_name : $contact->default_name) . '">';
        echo '<input type="submit" value="' . esc_attr__('Update Status e Nome', 'professionaldirectory') . '" class="button button-primary">';
        echo '</form>';

        // Exibe as pesquisas associadas
        if (!empty($searches)) {
            echo '<h2>' . esc_html__('Pesquisas Associadas', 'professionaldirectory') . '</h2>';
            echo '<ul>';
            // Dentro do loop que exibe as pesquisas associadas:
            foreach ($searches as $search) {
                echo '<li>';
                echo '<strong>Data da Pesquisa:</strong> ' . esc_html($search->search_date) . '<br>';
                echo '<strong>Tipo de Serviço:</strong> ' . esc_html($search->service_type) . '<br>';

                // Formulário para atualizar o status da pesquisa
                echo '<form method="post" action="">';
                wp_nonce_field('update_search_status_' . $search->id); // Certifique-se de gerar e verificar um nonce adequado
                echo '<select name="search_status">';
                foreach (['pendente', 'aprovado', 'rejeitado'] as $option) {
                    echo '<option value="' . esc_attr($option) . '"' . selected($search->search_status, $option, false) . '>' . esc_html(ucfirst($option)) . '</option>';
                }
                echo '</select>';
                echo '<input type="hidden" name="search_id" value="' . esc_attr($search->id) . '">';
                echo '<input type="submit" value="' . esc_attr__('Atualizar', 'professionaldirectory') . '" class="button">';
                echo '</form>';

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

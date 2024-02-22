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

if ($contact_id) {
    $contact = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$wpdb->prefix}pdr_contacts WHERE contact_id = %d", $contact_id));
    $status = $wpdb->get_var($wpdb->prepare("SELECT status FROM {$wpdb->prefix}pdr_author_contact_relations WHERE contact_id = %d AND author_id = %d", $contact_id, get_current_user_id()));
    $custom_name = $wpdb->get_var($wpdb->prepare("SELECT custom_name FROM {$wpdb->prefix}pdr_author_contact_relations WHERE contact_id = %d AND author_id = %d", $contact_id, get_current_user_id()));
    $searches = $wpdb->get_results($wpdb->prepare("SELECT * FROM {$wpdb->prefix}pdr_search_data WHERE contact_id = %d", $contact_id));

    echo '<div class="wrap">';
    echo '<div id="save-status"></div>';
    echo '<h1>' . esc_html__('Detalhes do Contato', 'professionaldirectory') . '</h1>';

    if ($contact) {
        echo '<form id="contact-form" method="post">';
        wp_nonce_field('update_contact_' . $contact_id, '_wpnonce', false);
        echo '<input type="hidden" name="action" value="save_contact_details">';
        echo '<input type="hidden" name="contact_id" value="' . esc_attr($contact_id) . '">';

        echo '<p><strong>Name:</strong> ' . esc_html($contact->default_name) . '</p>';
        echo '<p><strong>Email:</strong> ' . esc_html($contact->email) . '</p>';

        // Campo para status do contato
        echo '<label for="contact_status">Status:</label>';
        echo '<select name="contact_status" id="contact_status">';
        foreach (['active', 'lead', 'not_interested', 'client'] as $option) {
            echo '<option value="' . esc_attr($option) . '"' . selected($status, $option, false) . '>' . esc_html(ucfirst($option)) . '</option>';
        }
        echo '</select>';

        // Campo para editar o nome customizado
        echo '<p><strong>Custom Name:</strong></p>';
        echo '<input type="text" name="custom_name" value="' . esc_attr($custom_name ? $custom_name : $contact->default_name) . '">';

        // Seção de pesquisas
        if (!empty($searches)) {
            echo '<h2>' . esc_html__('Pesquisas Associadas', 'professionaldirectory') . '</h2>';
            foreach ($searches as $search) {
                echo '<div style="margin-bottom:30px;">';
                echo '<strong>ID da Pesquisa:</strong> ' . esc_html($search->id) . '<br>';
                echo '<strong>Data da Pesquisa:</strong> ' . esc_html($search->search_date) . '<br>';
                echo '<strong>Tipo de Serviço:</strong> ' . esc_html($search->service_type) . '<br>';
                echo '<strong>Status da Pesquisa:</strong>';
                echo '<select name="searches[' . esc_attr($search->id) . ']">';
                foreach (['pendente', 'aprovado', 'rejeitado'] as $option) {
                    echo '<option value="' . esc_attr($option) . '"' . selected($search->search_status, $option, false) . '>' . esc_html(ucfirst($option)) . '</option>';
                }
                echo '</select>';
                echo '</div>';
            }
        }

        echo '<button type="submit" class="button button-primary">' . esc_attr__('Salvar Todas as Modificações', 'professionaldirectory') . '</button>';
        echo '</form>';
    } else {
        echo '<p>' . esc_html__('Contato não encontrado.', 'professionaldirectory') . '</p>';
    }

    echo '</div>';
} else {
    wp_die(__('ID do contato não especificado.', 'professionaldirectory'));
}

// Inclusão do script JavaScript para tratamento do AJAX
$js_url = plugins_url('/js/alert-save-before-leave.js', __FILE__);
echo '<script src="' . esc_url($js_url) . '"></script>';

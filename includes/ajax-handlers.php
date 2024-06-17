<?php
defined('ABSPATH') or die('No script kiddies please!');

// Handler para salvar detalhes de contato via AJAX.
// Esta função é chamada através de uma requisição AJAX para salvar os detalhes do contato
 //e atualizar o status dos Inquiries associadas, incluindo o cálculo e ajuste das Referral Fees.
function rhb_save_contact_details_ajax_handler() {
    check_ajax_referer('update_contact_' . $_POST['contact_id'], 'nonce');

    if (!current_user_can('view_rhb_contacts')) {
        wp_send_json_error(['message' => 'Permissão insuficiente.']);
        exit;
    }

    global $wpdb;
    $contact_id = intval($_POST['contact_id']);
    $author_id = get_current_user_id();
    $custom_name = sanitize_text_field($_POST['custom_name']);
    $new_status = sanitize_text_field($_POST['contact_status']); // Garanta que este campo esteja sendo enviado corretamente
    $errors = false;

    $updated = $wpdb->update(
        "{$wpdb->prefix}rhb_author_contact_relations",
        [
            'custom_name' => $custom_name,
            'status' => $new_status // Assegure que está atualizando o status
        ],
        ['contact_id' => $contact_id, 'author_id' => $author_id]
    );

    if (!$updated && $wpdb->last_error) {
        error_log('Erro ao atualizar o contato: ' . $wpdb->last_error);
        wp_send_json_error(['message' => 'Erro ao atualizar o contato.']);
        exit;
    }

    foreach ($_POST['inquiries'] as $inquiry_id => $inquiry_status) {
        $inquiry_id_sanitized = intval($inquiry_id);
        $status_sanitized = sanitize_text_field($inquiry_status);

        require_once('referral-fee-calculator.php');
        $referralFees = calculate_referral_fees($author_id, $status_sanitized);

        $inquiry_updated = $wpdb->update("{$wpdb->prefix}rhb_inquiry_data", [
            'inquiry_status' => $status_sanitized,
            'referral_fee_value_view' => $referralFees['view'],
            'referral_fee_value_agreement_reached' => ($status_sanitized === 'agreement_reached') ? $referralFees['agreement_reached'] : 0.00
        ], [
            'id' => $inquiry_id_sanitized,
            'author_id' => $author_id
        ]);

        if (!$inquiry_updated && $wpdb->last_error) {
            error_log("Erro ao atualizar o status do Inquiry ID $inquiry_id: " . $wpdb->last_error);
            $errors = true;
        }
    }

    if ($errors) {
        wp_send_json_error(['message' => 'Erro ao atualizar o status de algumas ou todas as Inquiries.']);
    } else {
        wp_send_json_success(['message' => 'Informações atualizadas com sucesso.']);
    }

    exit;
}



// Handler para buscar taxas de referência não pagas
function handle_ajax_fetch_referral_fees() {
    if (!current_user_can('manage_options')) {
        wp_die('Acesso negado');
    }

    $filter_type = sanitize_text_field($_POST['filter']);
    $custom_start = sanitize_text_field($_POST['start_date']);
    $custom_end = sanitize_text_field($_POST['end_date']);

    $providers = get_unpaid_referral_fees(null, $filter_type, $custom_start, $custom_end);

    if (empty($providers)) {
        echo '<table border="1"><tr><th>Provider ID</th><th>Provider Name</th><th>Provider Email</th><th>Total Due</th></tr>';
        echo '<tr><td colspan="4">Nenhum dado encontrado para o período.</td></tr></table>';
    } else {
        echo '<table border="1"><tr><th>Provider ID</th><th>Provider Name</th><th>Provider Email</th><th>Total Due</th></tr>';
        foreach ($providers as $provider) {
            echo "<tr><td>" . esc_html($provider->provider_id) . "</td><td>" . esc_html($provider->provider_name) . "</td><td>" . esc_html($provider->provider_email) . "</td><td>" . esc_html($provider->total_due) . "</td></tr>";
        }
        echo '</table>';
    }

    wp_die(); // Finaliza a execução e retorna uma resposta adequada
}

add_action('wp_ajax_fetch_referral_fees', 'handle_ajax_fetch_referral_fees');




// Função para lidar com a criação de páginas.
function rhb_handle_create_pages() {
    $options = get_option('rhb_settings', []);
    $inquiry_page_id = isset($options['rhb_inquiry_page_id']) ? $options['rhb_inquiry_page_id'] : null;
    $page_exists = $inquiry_page_id && get_post_status($inquiry_page_id);

    if (isset($_POST['rhb_create_pages_submit']) && check_admin_referer('rhb_create_pages', 'rhb_create_pages_nonce')) {
        if (isset($_POST['create_inquiry_page']) && !$page_exists) {
            // Cria a página de Inquiry de serviços
            $page_id = wp_insert_post([
                'post_title' => __('Inquiry de Serviços', 'referralhub'),
                'post_content' => '[rhb_inquiry_form][rhb_inquiry_results]',
                'post_status' => 'publish',
                'post_type' => 'page'
            ]);
            if ($page_id) {
                $options['rhb_inquiry_page_id'] = $page_id;
                update_option('rhb_settings', $options);
                wp_redirect(admin_url('edit.php?post_type=rhb_service&page=rhb-setup-wizard&created=true'));
                exit;
            }
        }
    }
}
add_action('admin_init', 'rhb_handle_create_pages'); // Adiciona o handler para criar páginas
?>

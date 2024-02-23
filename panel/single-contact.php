<?php
ob_start();
if (!defined('WPINC')) {
    die;
}

if (WP_DEBUG) {
    @ini_set('log_errors', 'On');
    @ini_set('display_errors', 'Off');
    @ini_set('error_log', WP_CONTENT_DIR . '/debug.log');
}

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
    echo '<h1>' . esc_html__('Contact Details', 'professionaldirectory') . '</h1>';

    if ($contact) {
        echo '<div class= "contact-details">';
        echo '<div class= "pdr-column-left">';
        echo '<form id="contact-form" class="contact-details-form" method="post">';
        wp_nonce_field('update_contact_' . $contact_id, '_wpnonce', false);
        echo '<input type="hidden" name="action" value="save_contact_details">';
        echo '<input type="hidden" name="contact_id" value="' . esc_attr($contact_id) . '">';

        // Custom Name Field
        echo '<div class="form-field editable-field">';
        // echo '<label for="custom_name"><strong>Custom Name:</strong></label>';
        echo '<input type="text" id="custom_name" class="large-text" name="custom_name" value="' . esc_attr($custom_name ? $custom_name : $contact->default_name) . '" readonly>';
        echo '<button type="button" id="edit-name" class="edit-button"><i class="material-icons">edit</i></button>';
        echo '</div>';

        // Default Name Field
        echo '<div class="form-field">';
        echo '<span> <strong>(Default Name:</strong>';
        echo ' ' . esc_html($contact->default_name) . ')' . '</span>';
        echo '</div>';

        // Email Field
        echo '<div class="form-field">';
        echo '<label><strong>Email:</strong></label>';
        echo '<span>' . esc_html($contact->email) . '</span>';
        echo '</div>';
        echo '</div>';

        // Status Dropdown
        echo '<div class= "pdr-column-rigth">';
        echo '<div class="form-field client-status">';
        echo '<label for="contact_status"><strong>Status:</strong></label>';
        echo '<select name="contact_status" id="contact_status" class="regular-text">';
        foreach (['active', 'lead', 'not_interested', 'client'] as $option) {
            echo '<option value="' . esc_attr($option) . '"' . selected($status, $option, false) . '>' . esc_html(ucfirst($option)) . '</option>';
        }
        echo '</select>';
        echo '</div>';
        echo '</div>';
        echo '</div>';

        // Associated Searches Section
        if (!empty($searches)) {
            echo '<h2>' . esc_html__('Associated Searches', 'professionaldirectory') . '</h2>';
            foreach ($searches as $search) {
                echo '<div class="search-details">';
                echo '<p><strong>ID da Pesquisa:</strong> ' . esc_html($search->id) . '</p>';
                echo '<p><strong>Data da Pesquisa:</strong> ' . esc_html($search->search_date) . '</p>';
                echo '<p><strong>Tipo de Serviço:</strong> ' . esc_html($search->service_type) . '</p>';
                echo '<div class="form-field">';
                echo '<label for="search_status"><strong>Status da Pesquisa:</strong></label>';
                echo '<select name="searches[' . esc_attr($search->id) . ']" id="search_status_' . esc_attr($search->id) . '" class="regular-text">';
                foreach (['pending', 'approved', 'rejected'] as $option) {
                    echo '<option value="' . esc_attr($option) . '"' . selected($search->search_status, $option, false) . '>' . esc_html(ucfirst($option)) . '</option>';
                }
                echo '</select>';
                echo '</div>';
                echo '</div>';
            }
        }

        echo '<div class="form-field">';
        echo '<button type="submit" class="button button-primary">' . esc_attr__('Save All Changes', 'professionaldirectory') . '</button>';
        echo '</div>';
        echo '</form>';
    } else {
        echo '<p>' . esc_html__('No contact found.', 'professionaldirectory') . '</p>';
    }

    echo '</div>'; // .wrap
} else {
    wp_die(__('Contact ID not specified.', 'professionaldirectory'));
}

$js_url = plugins_url('/js/alert-save-before-leave.js', __FILE__);
echo '<script src="' . esc_url($js_url) . '"></script>';

<?php
defined('ABSPATH') or die('No script kiddies please!');

function get_unpaid_referral_fees($provider_id = null, $filter_type = 'all', $custom_start = null, $custom_end = null) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'rhb_inquiry_data';

    switch ($filter_type) {
        case 'this_week':
            $start_date = date('Y-m-d', strtotime('monday this week'));
            $end_date = date('Y-m-d 23:59:59', strtotime('sunday this week'));
            break;
        case 'this_month':
            $start_date = date('Y-m-01');
            $end_date = date('Y-m-t 23:59:59');
            break;
        case 'this_semester':
            $month = date('n');
            $start_date = $month < 7 ? date('Y-01-01') : date('Y-07-01');
            $end_date = $month < 7 ? date('Y-06-30 23:59:59') : date('Y-12-31 23:59:59');
            break;
        case 'this_year':
            $start_date = date('Y-01-01');
            $end_date = date('Y-12-31 23:59:59');
            break;
        case 'custom':
            $start_date = sanitize_text_field($custom_start);
            $end_date = date('Y-m-d 23:59:59', strtotime(sanitize_text_field($custom_end)));
            break;
        default:
            $start_date = null;
            $end_date = null;
            break;
    }

    $query = "SELECT author_id AS provider_id, user.user_nicename AS provider_name, user.user_email AS provider_email, SUM(referral_fee_value_view + referral_fee_value_agreement_reached) AS total_due
              FROM $table_name
              JOIN {$wpdb->users} user ON user.ID = author_id
              WHERE is_paid = 0";

    if (!is_null($start_date) && !is_null($end_date)) {
        $query = $wpdb->prepare($query . " AND inquiry_date >= %s AND inquiry_date <= %s GROUP BY provider_id", $start_date, $end_date);
    } else {
        $query .= " GROUP BY provider_id";
    }

    $results = $wpdb->get_results($query);

    return $results;
}


function render_referral_fees_table($providers) {
    ob_start();
    ?>
    <table border="1">
        <tr>
            <th>ID do Fornecedor</th>
            <th>Nome do Fornecedor</th>
            <th>Email do Fornecedor</th>
            <th>Total Devido</th>
        </tr>
        <?php if (!empty($providers)) : ?>
            <?php foreach ($providers as $provider) : ?>
                <tr>
                    <td><?php echo esc_html($provider->provider_id); ?></td>
                    <td><?php echo esc_html($provider->provider_name); ?></td>
                    <td><?php echo esc_html($provider->provider_email); ?></td>
                    <td><?php echo esc_html($provider->total_due); ?></td>
                </tr>
            <?php endforeach; ?>
        <?php else : ?>
            <tr><td colspan="4">Nenhum dado encontrado para o período.</td></tr>
        <?php endif; ?>
    </table>
    <?php
    return ob_get_clean();
}
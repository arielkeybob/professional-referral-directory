<?php
defined('ABSPATH') or die('No script kiddies please!');

$filter_type = isset($_GET['filter']) ? sanitize_text_field($_GET['filter']) : 'all';
$start_date = isset($_GET['start_date']) ? sanitize_text_field($_GET['start_date']) : null;
$end_date = isset($_GET['end_date']) ? sanitize_text_field($_GET['end_date']) : null;
$providers = get_unpaid_referral_fees(null, $filter_type, $start_date, $end_date);

echo '<h1>Referral Fees Report</h1>';
echo '<select id="period-selector" onchange="updateFilters()">';
echo '<option value="all">Todo o período</option>';
echo '<option value="this_week">Esta semana</option>';
echo '<option value="this_month">Este mês</option>';
echo '<option value="this_semester">Este semestre</option>';
echo '<option value="this_year">Este ano</option>';
echo '<option value="custom">Período personalizado</option>';
echo '</select>';

echo '<div id="custom-date-picker" style="display:none;">';
echo '<input type="date" id="start-date">';
echo '<input type="date" id="end-date">';
echo '<button onclick="fetchDataBasedOnDates()">Filtrar</button>';
echo '</div>';

echo '<div id="table-container"><table border="1">';
echo '<tr><th>Provider ID</th><th>Provider Name</th><th>Provider Email</th><th>Total Due</th></tr>';

foreach ($providers as $provider) {
    echo "<tr><td>" . esc_html($provider->provider_id) . "</td><td>" . esc_html($provider->provider_name) . "</td><td>" . esc_html($provider->provider_email) . "</td><td>" . esc_html($provider->total_due) . "</td></tr>";
}

echo '</table></div>';

// Referência ao script JavaScript
// No final do arquivo PHP, antes de fechar a tag </body>
echo '<script src="' . plugins_url('/js/admin-referral-fees.js', dirname(__FILE__)) . '"></script>';
echo '<script type="text/javascript">
    var ajaxurl = "' . admin_url('admin-ajax.php') . '";
</script>';

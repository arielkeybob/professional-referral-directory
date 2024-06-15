<?php
defined('ABSPATH') or die('No script kiddies please!');

$filter_type = isset($_GET['filter']) ? $_GET['filter'] : 'all';
$start_date = isset($_GET['start_date']) ? $_GET['start_date'] : null;
$end_date = isset($_GET['end_date']) ? $_GET['end_date'] : null;
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

echo '<table border="1">';
echo '<tr><th>Provider ID</th><th>Provider Name</th><th>Provider Email</th><th>Total Due</th></tr>';

foreach ($providers as $provider) {
    echo "<tr><td>{$provider->provider_id}</td><td>{$provider->provider_name}</td><td>{$provider->provider_email}</td><td>{$provider->total_due}</td></tr>";
}

echo '</table>';

echo '<script>
function updateFilters() {
    var selector = document.getElementById("period-selector");
    var datePicker = document.getElementById("custom-date-picker");
    if(selector.value === "custom") {
        datePicker.style.display = "block";
    } else {
        datePicker.style.display = "none";
        window.location.href = "?post_type=rhb_service&page=rhb-referral-fees&filter=" + selector.value;
    }
}

function fetchDataBasedOnDates() {
    var startDate = document.getElementById("start-date").value;
    var endDate = document.getElementById("end-date").value;
    window.location.href = "?post_type=rhb_service&page=rhb-referral-fees&filter=custom&start_date=" + startDate + "&end_date=" + endDate;
}
</script>';

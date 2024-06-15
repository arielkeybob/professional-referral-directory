<?php
defined('ABSPATH') or die('No script kiddies please!');

// Supondo que o arquivo de includes/referral-fees.php tenha sido incluído onde necessário
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['start_date'], $_POST['end_date'])) {
    $start_date = date('Y-m-d', strtotime($_POST['start_date']));
    $end_date = date('Y-m-d', strtotime($_POST['end_date']));
    $providers = get_unpaid_referral_fees(null, $start_date, $end_date);
} else {
    $providers = get_unpaid_referral_fees();  // Chamada sem filtro de data
}

?>
<form method="post" action="">
    <label for="start_date">Data Inicial:</label>
    <input type="date" id="start_date" name="start_date" required>

    <label for="end_date">Data Final:</label>
    <input type="date" id="end_date" name="end_date" required>

    <input type="submit" value="Filtrar">
</form>

<?php
echo '<table border="1">';
echo '<tr><th>Provider ID</th><th>Provider Name</th><th>Provider Email</th><th>Total Due</th></tr>';

foreach ($providers as $provider) {
    echo "<tr><td>{$provider->provider_id}</td><td>{$provider->provider_name}</td><td>{$provider->provider_email}</td><td>{$provider->total_due}</td></tr>";
}

echo '</table>';
?>

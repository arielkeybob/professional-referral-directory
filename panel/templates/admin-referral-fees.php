<?php
defined('ABSPATH') or die('No script kiddies please!');

// Supondo que o arquivo de includes/referral-fees.php tenha sido incluído onde necessário
$providers = get_unpaid_referral_fees(); // Chamada sem filtro para iniciar

if (!$providers) {
    error_log('No providers or no unpaid fees found.');
}

echo '<table border="1">';
echo '<tr><th>Provider ID</th><th>Total Due</th></tr>';

foreach ($providers as $provider) {
    echo "<tr><td>{$provider->provider_id}</td><td>{$provider->total_due}</td></tr>";
    error_log("Rendering provider ID: {$provider->provider_id} with total due: {$provider->total_due}");
}

echo '</table>';

<?php
defined('ABSPATH') or die('No script kiddies please!');

$providers = get_unpaid_referral_fees(); // Chamada sem filtro para iniciar

echo '<h1>Unpaid Referral Fees</h1>';
echo '<table>';
echo '<thead><tr><th>Provider ID</th><th>Provider Name</th><th>Provider Email</th><th>Total Due</th></tr></thead>';
echo '<tbody>';

foreach ($providers as $provider) {
    echo "<tr><td>{$provider->provider_id}</td><td>{$provider->provider_name}</td><td>{$provider->provider_email}</td><td>$" . number_format($provider->total_due, 2) . "</td></tr>";
}

echo '</tbody></table>';

?>

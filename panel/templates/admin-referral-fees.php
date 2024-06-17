<?php
defined('ABSPATH') or die('No script kiddies please!');

$filter_type = isset($_GET['filter']) ? sanitize_text_field($_GET['filter']) : 'all';
$start_date = isset($_GET['start_date']) ? sanitize_text_field($_GET['start_date']) : null;
$end_date = isset($_GET['end_date']) ? sanitize_text_field($_GET['end_date']) : null;
$providers = get_unpaid_referral_fees(null, $filter_type, $start_date, $end_date);

$period_options = [
    'all' => 'Todo o período',
    'this_week' => 'Esta semana',
    'this_month' => 'Este mês',
    'this_semester' => 'Este semestre',
    'this_year' => 'Este ano',
    'custom' => 'Período personalizado',
];
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Relatório de Taxas de Referência</title>
    <style>
        #custom-date-picker {
            display: none;
        }
    </style>
</head>
<body>
    <h1>Relatório de Taxas de Referência</h1>
    <select id="period-selector" onchange="updateFilters()">
        <?php foreach ($period_options as $value => $label) : ?>
            <option value="<?php echo esc_attr($value); ?>"><?php echo esc_html($label); ?></option>
        <?php endforeach; ?>
    </select>

    <div id="custom-date-picker">
        <input type="date" id="start-date" value="<?php echo esc_attr($start_date); ?>">
        <input type="date" id="end-date" value="<?php echo esc_attr($end_date); ?>">
        <button onclick="fetchDataBasedOnDates()">Filtrar</button>
    </div>

    <div id="table-container">
        <table border="1">
            <thead>
                <tr>
                    <th>Provider ID</th>
                    <th>Provider Name</th>
                    <th>Provider Email</th>
                    <th>Total Due</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($providers) : ?>
                    <?php foreach ($providers as $provider) : ?>
                        <tr>
                            <td><?php echo esc_html($provider->provider_id); ?></td>
                            <td><?php echo esc_html($provider->provider_name); ?></td>
                            <td><?php echo esc_html($provider->provider_email); ?></td>
                            <td><?php echo esc_html($provider->total_due); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="4">Nenhum dado encontrado para o período.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <script src="<?php echo esc_url(plugins_url('/js/admin-panel-referral-fees.js', dirname(__FILE__))); ?>"></script>
    <script type="text/javascript">
        var ajaxurl = "<?php echo esc_url(admin_url('admin-ajax.php')); ?>";
    </script>
</body>
</html>

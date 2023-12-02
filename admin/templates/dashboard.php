<?php
// Verifique se este arquivo não está sendo acessado diretamente.
if (!defined('WPINC')) {
    die;
}

require_once __DIR__ . '/../../includes/dashboard-functions.php';

?>

<!-- Início do Dashboard -->
<div class="wrap">
    <h1>Dashboard do Professional</h1>

    <!-- Seção de Total de Pesquisas por Serviço -->
    <div class="card">
    <div class="card-header">Total de Pesquisas por Serviço</div>
    <div class="card-body">
        <?php
        $services = pdr_get_services_by_current_user();
        if (!empty($services)) {
            echo '<ul>';
            foreach ($services as $service) {
                $total_searches = pdr_get_total_searches_by_service($service['ID']);
                echo '<li>' . esc_html($service['post_title']) . ' - Pesquisas: ' . esc_html($total_searches) . '</li>';
            }
            echo '</ul>';
        } else {
            echo '<p>Nenhum serviço encontrado.</p>';
        }
        ?>
    </div>
</div>


    <!-- Seção de Pesquisas Recentes -->
    <div class="card">
    <div class="card-header">Pesquisas Recentes</div>
    <div class="card-body">
        <?php
        $recent_searches = pdr_get_recent_searches_for_user();
        if (!empty($recent_searches)) {
            echo '<table id="recentSearchesTable" class="display">';
            echo '<thead><tr><th>Nome do Usuário</th><th>Email</th><th>Endereço</th><th>Service</th><th>Type</th><th>Date</th></tr></thead><tbody>';
            foreach ($recent_searches as $search) {
                echo '<tr>';
                echo '<td>' . esc_html($search['name']) . '</td>';
                echo '<td>' . esc_html($search['email']) . '</td>';
                echo '<td>' . esc_html($search['address']) . '</td>';
                echo '<td>' . esc_html($search['post_title']) . '</td>';
                echo '<td>' . esc_html($search['service_type']) . '</td>';
                echo '<td>' . esc_html($search['search_date']) . '</td>';
                echo '</tr>';
            }
            echo '</tbody></table>';
        } else {
            echo '<p>Nenhum resultado recente encontrado.</p>';
        }
        ?>
    </div>
</div>

</div>

<!-- Inclua o DataTables CSS e JS -->
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.22/css/jquery.dataTables.css">
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.js"></script>

<script>
jQuery(document).ready(function($) {
    $('#recentSearchesTable').DataTable(); // Inicializa a DataTable
});
</script>


    <!-- Seção de Distribuição das Pesquisas por Tipo de Serviço -->
    <div class="card">
        <div class="card-header">Distribuição das Pesquisas por Tipo de Serviço</div>
        <div class="card-body">
            <canvas id="chartServiceTypeDistribution"></canvas>
        </div>
    </div>
    <!-- Outras seções... -->
</div>
<!-- Fim do Dashboard -->

<!-- Scripts para Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
jQuery(document).ready(function($) {
    var serviceTypeData = <?php echo json_encode(pdr_get_searches_distribution_by_service_type()); ?>;
    var labels = serviceTypeData.map(function(item) { return item.service_type; });
    var data = serviceTypeData.map(function(item) { return item.total; });

    var ctx = document.getElementById('chartServiceTypeDistribution').getContext('2d');
    var chart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Número de Pesquisas',
                data: data,
                backgroundColor: 'rgba(0, 123, 255, 0.5)'
            }]
        },
        options: {
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true,
                        stepSize: 1, // Isso deve garantir que o gráfico suba em incrementos de 1
                        suggestedMax: Math.max(...data) + 1 // Um pouco acima do valor máximo
                    }
                }]
            },
            // Adicionando essa propriedade para evitar que o Chart.js arredonde os valores
            responsive: true,
            maintainAspectRatio: false
        }
    });
});
</script>



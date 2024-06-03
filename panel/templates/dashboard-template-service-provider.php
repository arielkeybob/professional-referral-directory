<?php
    defined('ABSPATH') or die('No script kiddies please!');

require_once __DIR__ . '/../../panel/dashboard-service-provider-functions.php';

// Enfileiramento do Bootstrap e Chart.js no admin do WordPress deve ser feito em outra parte do plugin, não diretamente aqui

?>
<!-- Estilos personalizados para sobrescrever o Bootstrap -->
<style>
    .card {
        max-width: none;
        padding:0;
    }
    .card-body {
        padding: 2rem; /* Ajuste o padding se necessário */
    }
</style>
<!-- Início do Dashboard -->
<div class="wrap">
    <h1><?php echo esc_html__('Service Provider Dashboard ', 'professionaldirectory'); ?></h1>

    <div class="row"> <!-- Início da row -->
        <!-- Seção de Total de Inquiries por Serviço -->
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header"><?php echo esc_html__('Total Inquiries by Service.', 'professionaldirectory'); ?></div>
                <div class="card-body">
                    <?php
                    $services = pdr_get_services_by_current_user();
                    if (!empty($services)) {
                        echo '<ul>';
                        foreach ($services as $service) {
                            $total_inquiries = pdr_get_total_inquiries_by_service($service['ID']);
                            echo '<li>' . esc_html($service['post_title']) . ' - ' . esc_html__('Inquiry:', 'professionaldirectory') . ' ' . esc_html($total_inquiries) . '</li>';
                        }
                        echo '</ul>';
                    } else {
                        echo '<p>' . esc_html__('No service found.', 'professionaldirectory') . '</p>';
                    }
                    ?>
                </div>
            </div>
        </div>

        <!-- Seção de Distribuição das Inquiries por Tipo de Serviço -->
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header"><?php echo esc_html__('Distribution of Inquiries by Service Type', 'professionaldirectory'); ?></div>
                <div class="card-body">
                    <canvas id="chartServiceTypeDistribution"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>
 <!-- Fim da row -->

    <!-- Seção de Inquiries Recentes -->
    <div class="card mb-4 w-100"> <!-- Classe w-100 para largura total -->
    <div class="card-header"><?php echo esc_html__('Recent Inquiries', 'professionaldirectory'); ?></div>
    <div class="card-body">
        <?php
        $recent_inquiries = pdr_get_recent_inquiries_for_user();
        if (!empty($recent_inquiries)) {
            echo '<table id="recentInquiriesTable" class="display">';
            echo '<thead><tr><th>' . esc_html__('User Name', 'professionaldirectory') . '</th><th>' . esc_html__('Email', 'professionaldirectory') . '</th><th>' . esc_html__('Address', 'professionaldirectory') . '</th><th>' . esc_html__('Service', 'professionaldirectory') . '</th><th>' . esc_html__('Type', 'professionaldirectory') . '</th><th>' . esc_html__('Date', 'professionaldirectory') . '</th></tr></thead><tbody>';
            foreach ($recent_inquiries as $inquiry) {
                $details_url = wp_nonce_url(
                    add_query_arg(['page' => 'pdr-contact-details', 'contact_id' => $inquiry['contact_id']], admin_url('admin.php')),
                    'view_contact_details_' . $inquiry['contact_id'],
                    'contact_nonce'
                );
                echo '<tr>';
                echo '<td><a href="' . esc_url($details_url) . '">' . esc_html($inquiry['name']) . '</a></td>'; // Link para os detalhes do contato
                echo '<td>' . esc_html($inquiry['email']) . '</td>';
                echo '<td>' . esc_html($inquiry['service_location']) . '</td>';
                echo '<td>' . esc_html($inquiry['post_title']) . '</td>';
                echo '<td>' . esc_html($inquiry['service_type']) . '</td>';
                echo '<td>' . esc_html($inquiry['inquiry_date']) . '</td>';
                echo '</tr>';
            }
            echo '</tbody></table>';
        } else {
            echo '<p>' . esc_html__('No recent results found.', 'professionaldirectory') . '</p>';
        }
        ?>
    </div>
</div>


    <!-- Outras seções... -->

</div>
<!-- Fim do Dashboard -->

<!-- Inclua o DataTables CSS e JS -->
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.22/css/jquery.dataTables.css">
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.js"></script>

<script>
jQuery(document).ready(function($) {
    $('#recentInquiriesTable').DataTable(); // Inicializa a DataTable
});
</script>

<!-- Scripts para Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
jQuery(document).ready(function($) {
    var serviceTypeData = <?php echo json_encode(pdr_get_inquiries_distribution_by_service_type()); ?>;
    var labels = serviceTypeData.map(function(item) { return item.service_type; });
    var data = serviceTypeData.map(function(item) { return item.total; });

    var ctx = document.getElementById('chartServiceTypeDistribution').getContext('2d');
    var chart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Número de Inquiries',
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

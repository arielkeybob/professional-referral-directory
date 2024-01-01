<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" type="text/css" href="css/dashboard-style-admin.css">
    <script src="js/dashboard-script-admin.js"></script>
</head>
<body>
    <div id="dashboard-container">
        <h1>Dashboard do Admin</h1>
        <div>
            <label for="period-selector">Selecionar Período:</label>
            <select id="period-selector">
                <option value="today">Hoje</option>
                <option value="last_week">Última Semana</option>
                <option value="last_month">Último Mês</option>
                <option value="this_year">Este Ano</option>
                <option value="custom">Personalizado</option>
            </select>
            <div id="custom-period" style="display:none;">
                <input type="date" id="start-date">
                <input type="date" id="end-date">
            </div>
            <button id="load-data">Carregar Dados</button>
        </div>
        <div>
            <input type="checkbox" id="show-services-without-search">
            <label for="show-services-without-search">Exibir também serviços sem pesquisas no período</label>
        </div>
        <table id="services-data">
            <thead>
                <tr>
                    <th>Nome do Serviço</th>
                    <th>Quantidade de Pesquisas</th>
                    <th>Nome do Autor</th>
                    <th>Data da Última Pesquisa</th>
                </tr>
            </thead>
            <tbody>
                <!-- Os dados dos serviços serão inseridos aqui -->
            </tbody>
        </table>
    </div>
    <?php wp_nonce_field('fetch_services_nonce', 'fetch_services_nonce_field'); ?>
    <script src="js/dashboard-script-admin.js"></script>
</body>
</html>

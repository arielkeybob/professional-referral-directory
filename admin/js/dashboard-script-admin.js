document.addEventListener('DOMContentLoaded', function() {
    const periodSelector = document.getElementById('period-selector');
    const customPeriodDiv = document.getElementById('custom-period');
    const loadDataButton = document.getElementById('load-data');
    const servicesTableBody = document.getElementById('services-data').getElementsByTagName('tbody')[0];

    periodSelector.addEventListener('change', function() {
        customPeriodDiv.style.display = this.value === 'custom' ? 'block' : 'none';
    });

    loadDataButton.addEventListener('click', function() {
        const period = periodSelector.value;
        const includeNoSearch = document.getElementById('show-services-without-search').checked;
        const startDate = document.getElementById('start-date').value;
        const endDate = document.getElementById('end-date').value;

        // Preparar os dados para a requisição AJAX
        const data = {
            'action': 'fetch_services',
            'nonce': myPlugin.ajax_nonce, // Certifique-se de passar o nonce corretamente
            'period': period,
            'include_no_search': includeNoSearch,
            'start_date': startDate,
            'end_date': endDate
        };

        // Enviar a requisição AJAX
        // Enviar a requisição AJAX
        jQuery.post(myPlugin.ajax_url, data, function(response) {
            if (response.success) {
                // Limpar a tabela existente
                servicesTableBody.innerHTML = '';

                // Adicionar novas linhas à tabela com os dados retornados
                response.data.forEach(function(service) {
                    const row = servicesTableBody.insertRow();
                    row.insertCell(0).textContent = service.service_name; // Nome do serviço
                    row.insertCell(1).textContent = service.search_count; // Quantidade de pesquisas
                    row.insertCell(2).textContent = service.author_name; // Nome do autor
                    row.insertCell(3).textContent = service.last_search; // Data da última pesquisa
                });
            } else {
                // Lidar com erros ou dados vazios
                servicesTableBody.innerHTML = '<tr><td colspan="4">Nenhum dado encontrado</td></tr>';
            }
        });
    });
});

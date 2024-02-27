document.addEventListener('DOMContentLoaded', function() {
    const periodSelector = document.getElementById('period-selector');
    const loadDataButton = document.getElementById('load-data');
    const servicesDataTable = document.getElementById('services-data');

    // Verifique se o elemento da tabela existe antes de tentar acessar seu corpo
    if (servicesDataTable) {
        const servicesTableBody = servicesDataTable.getElementsByTagName('tbody')[0];

        loadDataButton.addEventListener('click', function() {
            const period = periodSelector.value;
            const includeNoSearch = document.getElementById('show-services-without-search').checked;
            const startDate = document.getElementById('start-date').value;
            const endDate = document.getElementById('end-date').value;

            // Preparar os dados para a requisição AJAX
            const data = {
                'action': 'fetch_admin_dashboard_data',
                'nonce': pdrAjax.ajax_nonce,
                'period': period,
                'include_no_search': includeNoSearch,
                'start_date': startDate,
                'end_date': endDate
            };

            // Enviar a requisição AJAX
            jQuery.post(pdrAjax.ajax_url, data, function(response) {
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
            }).fail(function(jqXHR, textStatus, errorThrown) {
                // Lidar com falha na requisição AJAX
                console.error('Erro na requisição AJAX: ' + textStatus, errorThrown);
                servicesTableBody.innerHTML = '<tr><td colspan="4">Erro ao carregar dados</td></tr>';
            });
        });
    } else {
        console.error('Elemento "services-data" não encontrado no DOM.');
    }
});

jQuery(document).ready(function($) {
    // Primeira Etapa: Busca por Tipo de Serviço e Endereço
    $('#pdr-search-btn').click(function() {
        var data = {
            'action': 'pdr_search',
            'service_type': $('select[name="service_type"]').val(),
            'address': $('input[name="address"]').val()
        };
        $.post(pdr_ajax_object.ajax_url, data, function(response) {
            if (response.success) {
                // Exibir resultados da busca
                displayResults(response.data);
                // Exibir formulário de informações pessoais
                $('#pdr-personal-info-form').show();
            }
        });
    });

    // Segunda Etapa: Envio de Nome e E-mail
    $('#pdr-submit-personal-info').click(function() {
        var data = {
            'action': 'pdr_search',
            'name': $('input[name="name"]').val(),
            'email': $('input[name="email"]').val()
        };
        $.post(pdr_ajax_object.ajax_url, data, function(response) {
            if (response.success) {
                alert('Informações enviadas com sucesso.');
            }
        });
    });

    // Função para exibir os resultados da busca
    function displayResults(data) {
        var resultsContainer = $('#pdr-search-results');
        resultsContainer.empty();

        if (data && data.length > 0) {
            data.forEach(function(service) {
                resultsContainer.append('<div class="service-result">' +
                    '<h3>' + service.name + '</h3>' +
                    '<p>ID: ' + service.id + '</p>' +
                    // Inclua outros detalhes do serviço conforme necessário
                    '</div>');
            });
        } else {
            resultsContainer.append('<p>Nenhum serviço encontrado.</p>');
        }
        resultsContainer.show();
    }
});

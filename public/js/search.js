jQuery(document).ready(function($) {
    // Exibir campos de nome e e-mail ao clicar em "Next"
    $('#pdr-search-btn').click(function() {
        $('#pdr-personal-info-form').show();
        $(this).hide(); // Esconde o botão "Next" após o clique
    });

    // Evento de submissão do formulário de busca
    $('#pdr-search-form').on('submit', function(e) {
        e.preventDefault();

        var formData = {
            'action': 'pdr_search',
            'service_type': $('select[name="service_type"]').val(),
            'address': $('input[name="address"]').val(), // Por enquanto não utilizado
            'name': $('input[name="name"]').val(),
            'email': $('input[name="email"]').val()
        };

        // Enviando a requisição AJAX
        $.ajax({
            url: ajax_object.ajax_url, // Certifique-se de que ajaxurl está definido corretamente
            type: 'POST',
            data: formData,
            success: function(response) {
                if (response.success) {
                    // Função para processar e exibir os resultados da busca
                    displayResults(response.data);
                    alert('Informações enviadas com sucesso.');
                } else {
                    // Tratar casos de falha na busca
                    $('#pdr-search-results').html('<p>Nenhum serviço encontrado.</p>');
                }
            },
            error: function() {
                // Tratamento de erro
                $('#pdr-search-results').html('<p>Erro ao processar a busca.</p>');
            }
        });
    });

    // Função para exibir os resultados da busca
    function displayResults(data) {
        var resultsContainer = $('#pdr-search-results');
        resultsContainer.empty();

        if (data && data.length > 0) {
            $.each(data, function(index, service) {
                resultsContainer.append('<div class="service-result">' +
                    '<h3>' + service.name + '</h3>' +
                    '<p>ID: ' + service.id + '</p>' +
                    // Inclua outros detalhes do serviço conforme necessário
                    '</div>');
            });
        } else {
            resultsContainer.append('<p>Nenhum serviço encontrado.</p>');
        }
    }
});

jQuery(document).ready(function($) {
    // Exibir campos de nome e e-mail ao clicar em "Next"
    $('#pdr-search-btn').click(function() {
        $('#pdr-personal-info-form').show();
        $(this).hide(); // Opcional: esconde o botão "Next" após o clique
    });

    // Evento de submissão do formulário de busca
    $('#pdr-search-form').on('submit', function(e) {
        e.preventDefault();

        var formData = {
            'action': 'pdr_search',
            'service_type': $('select[name="service_type"]').val(),
            'address': $('input[name="address"]').val(),
            'name': $('input[name="name"]').val(),
            'email': $('input[name="email"]').val()
        };

        // Enviando a requisição AJAX
        $.ajax({
            url: ajaxurl,
            type: 'POST',
            data: formData,
            success: function(response) {
                if (response.success) {
                    displayResults(response.data);
                    alert('Informações enviadas com sucesso.');
                }
            },
            error: function() {
                $('#pdr-search-results').html('<p>Erro ao processar a busca.</p>');
            }
        });
    });

    // Função para exibir os resultados da busca
    function displayResults(data) {
        var resultsContainer = $('#pdr-search-results');
        resultsContainer.empty();

        // Lógica para exibir os resultados
        // ...
    }
});

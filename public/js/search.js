// Inicializa o Autocomplete do Google Places no campo de endereço
// Definição global da função initAutocomplete
/*
window.initAutocomplete = function() {
    var autocomplete = new google.maps.places.Autocomplete(
        document.getElementById('pdr_service_location'),
        {types: ['geocode']}
    );

    autocomplete.addListener('place_changed', function() {
        var place = autocomplete.getPlace();
        if (!place.geometry) {
            alert("No details available for input: '" + place.name + "'");
            return;
        }

        // Atualize os campos de latitude e longitude
        document.getElementById('pdr_latitude_display').textContent = place.geometry.location.lat();
        document.getElementById('pdr_longitude_display').textContent = place.geometry.location.lng();
    });
};
*/

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
            'service_location': $('select[name="service_location"]').val(),
            'name': $('input[name="name"]').val(),
            'email': $('input[name="email"]').val(),
            'create_account': $('input[name="create_account"]').is(':checked') ? 1 : 0,
            'password': $('input[name="password"]').val(),
            'confirm_password': $('input[name="confirm_password"]').val()
        };

        // Enviando a requisição AJAX
        $.ajax({
            url: ajax_object.ajax_url,
            type: 'POST',
            data: formData,
            success: function(response) {
                if (response.success) {
                    // Função para processar e exibir os resultados da busca
                    displayResults(response.data);
                } else {
                    // Tratar casos de falha na busca
                    $('#pdr-search-results').html('<p>' + response.data + '</p>');
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

        // Insere o HTML retornado diretamente no container de resultados
        resultsContainer.html(data);
    }
});




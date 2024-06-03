// Inicializa o Autocomplete do Google Places no campo de endereço
// Definição global da função initAutocomplete
/*
window.initAutocomplete = function() {
    var autocomplete = new google.maps.places.Autocomplete(
        document.getElementById('rhb_service_location'),
        {types: ['geocode']}
    );

    autocomplete.addListener('place_changed', function() {
        var place = autocomplete.getPlace();
        if (!place.geometry) {
            alert("No details available for input: '" + place.name + "'");
            return;
        }

        // Atualize os campos de latitude e longitude
        document.getElementById('rhb_latitude_display').textContent = place.geometry.location.lat();
        document.getElementById('rhb_longitude_display').textContent = place.geometry.location.lng();
    });
};
*/

jQuery(document).ready(function($) {
    // Exibir campos de nome e e-mail ao clicar em "Next"
    $('#rhb-inquiry-btn').click(function() {
        $('#rhb-personal-info-form').show();
        $(this).hide(); // Esconde o botão "Next" após o clique
    });

    // Evento de submissão do formulário de busca
    $('#rhb-inquiry-form').on('submit', function(e) {
        e.preventDefault();

        var formData = {
            'action': 'rhb_inquiry',
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
                    $('#rhb-inquiry-results').html('<p>' + response.data + '</p>');
                }
            },
            error: function() {
                // Tratamento de erro
                $('#rhb-inquiry-results').html('<p>Erro ao processar a busca.</p>');
            }
        });
    });

    // Função para exibir os resultados da busca
    function displayResults(data) {
        var resultsContainer = $('#rhb-inquiry-results');
        resultsContainer.empty();

        // Insere o HTML retornado diretamente no container de resultados
        resultsContainer.html(data);
    }
});




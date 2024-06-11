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
    $('#rhb-inquiry-btn').click(function() {
        $('#rhb-personal-info-form').show();
        $(this).hide(); // Esconde o botão "Next" após o clique
    });

    $('#rhb-inquiry-form').on('submit', function(e) {
        e.preventDefault();

        $('#loading-spinner').show(); // Mostra o spinner

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
                $('#loading-spinner').hide(); // Esconde o spinner
                if (response.success) {
                    // Função para processar e exibir os resultados da busca
                    $('#rhb-inquiry-results').html(response.data);
                } else {
                    // Tratar casos de falha na busca
                    $('#rhb-inquiry-results').html('<p>' + response.data + '</p>');
                }
            },
            error: function() {
                $('#loading-spinner').hide(); // Esconde o spinner
                $('#rhb-inquiry-results').html('<p>Erro ao processar a busca.</p>');
            }
        });
    });
});





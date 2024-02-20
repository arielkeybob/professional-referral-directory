jQuery(document).ready(function($) {
    // Atualizar status do contato via AJAX
    $('#contact_status').change(function() {
        var contactId = $(this).closest('form').find('input[name="contact_id"]').val();
        var newStatus = $(this).val();
        console.log('Atualizando status do contato:', contactId, newStatus);

        $.ajax({
            url: pdrPanelAjax.ajax_url,
            method: 'POST',
            data: {
                action: 'salvar_status_contato',
                contact_id: contactId,
                contact_status: newStatus,
                security: pdrPanelAjax.ajax_nonce
            },
            success: function(response) {
                console.log('Resposta do salvar_status_contato:', response);
                alert('Status do contato atualizado com sucesso.');
            },
            error: function(xhr, status, error) {
                console.error('Erro no salvar_status_contato:', xhr, status, error);
                alert('Erro ao atualizar status do contato.');
            }
        });
    });

    // Atualizar status da pesquisa via AJAX
    $('.search_status').change(function() {
        var searchId = $(this).data('search-id');
        var newStatus = $(this).val();
        console.log('Atualizando status da pesquisa:', searchId, newStatus);

        $.ajax({
            url: pdrPanelAjax.ajax_url,
            method: 'POST',
            data: {
                action: 'salvar_status_pesquisa',
                search_id: searchId,
                search_status: newStatus,
                security: pdrPanelAjax.ajax_nonce // Garanta que este nonce corresponda à ação no servidor
            },
            success: function(response) {
                console.log('Resposta do salvar_status_pesquisa:', response);
                alert('Status da pesquisa atualizado com sucesso.');
            },
            error: function(xhr, status, error) {
                console.error('Erro no salvar_status_pesquisa:', xhr, status, error);
                alert('Erro ao atualizar status da pesquisa.');
            }
        });
    });
});

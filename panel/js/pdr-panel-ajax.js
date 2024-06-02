jQuery(document).ready(function($) {
    // Atualizar status do contato via AJAX (Parte comentada previamente, mantida para referência)
    /*
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
                contact_status: newStatus
                // Removido: security: pdrPanelAjax.ajax_nonce
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
    */

    // Atualizar status do Inquiry via AJAX
    $('.inquiry_status').change(function() {
        var inquiryId = $(this).data('inquiry-id');
        var newStatus = $(this).val();
        console.log('Atualizando status do Inquiry:', inquiryId, newStatus);

        $.ajax({
            url: pdrPanelAjax.ajax_url,
            method: 'POST',
            data: {
                action: 'save_inquiry_status',
                inquiry_id: inquiryId,
                inquiry_status: newStatus
                // Removido: security: pdrPanelAjax.ajax_nonce
            },
            success: function(response) {
                console.log('Resposta do save_inquiry_status:', response);
                alert('Status do Inquiry atualizado com sucesso.');
            },
            error: function(xhr, status, error) {
                console.error('Erro no save_inquiry_status:', xhr, status, error);
                alert('Erro ao atualizar status do Inquiry.');
            }
        });
    });
});

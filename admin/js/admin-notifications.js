jQuery(document).on('click', '.pdr-notificacao button.notice-dismiss', function() {
    var notificacaoId = jQuery(this).closest('.pdr-notificacao').data('notificacao-id');
    jQuery.post(ajaxurl, {
        action: 'pdr_notificacao_fechada',
        notificacao_id: notificacaoId
    });
});

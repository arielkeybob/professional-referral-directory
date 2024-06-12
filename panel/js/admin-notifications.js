jQuery(document).ready(function($) {
    // Realoca notificações para dentro do slider especificado
    $('.rhb-notification').detach().appendTo('.rhb-notification-slider');

    // Inicializa o índice da notificação atual e obtém o total de notificações
    var currentNotification = 0;
    var totalNotifications = $('.rhb-notification').length;

    // Esconde todas as notificações e mostra apenas a primeira
    $('.rhb-notification').hide();
    if (totalNotifications > 0) {
        $('.rhb-notification').eq(currentNotification).show();
    }

    // Adiciona a funcionalidade de navegação 'Próximo'
    $('.rhb-notification-controls .next').click(function() {
        $('.rhb-notification').eq(currentNotification).hide();
        currentNotification = (currentNotification + 1) % totalNotifications;
        $('.rhb-notification').eq(currentNotification).show();
    });

    // Adiciona a funcionalidade de navegação 'Anterior'
    $('.rhb-notification-controls .prev').click(function() {
        $('.rhb-notification').eq(currentNotification).hide();
        currentNotification = (currentNotification - 1 + totalNotifications) % totalNotifications;
        $('.rhb-notification').eq(currentNotification).show();
    });

    // Adiciona o evento de clique para fechar a notificação
    $(document).on('click', '.rhb-notification button.notice-dismiss', function() {
        var notificationId = $(this).closest('.rhb-notification').data('notification-id');
        // Faz a requisição AJAX para marcar a notificação como fechada
        $.post(ajaxurl, {
            action: 'rhb_notification_fechada',
            notification_id: notificationId
        }, function(response) {
            // Opções de callback após fechar a notificação, se necessário
        });
        
        // Remove a notificação atual da exibição e ajusta o índice e total
        $(this).closest('.rhb-notification').remove();
        totalNotifications--;
        if (currentNotification >= totalNotifications) {
            currentNotification = 0; // Reset para o início se a última notificação foi fechada
        }
        $('.rhb-notification').eq(currentNotification).show(); // Mostra a próxima notificação
    });
});

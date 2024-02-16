jQuery(document).on('click', '.pdr-notification button.notice-dismiss', function() {
    var notificationId = jQuery(this).closest('.pdr-notification').data('notification-id');
    jQuery.post(ajaxurl, {
        action: 'pdr_notification_fechada',
        notification_id: notificationId
    });
});

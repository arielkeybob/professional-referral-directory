jQuery(document).on('click', '.rhb-notification button.notice-dismiss', function() {
    var notificationId = jQuery(this).closest('.rhb-notification').data('notification-id');
    jQuery.post(ajaxurl, {
        action: 'rhb_notification_fechada',
        notification_id: notificationId
    });
});

<?php
defined('ABSPATH') or die('No script kiddies please!');

class RHB_Media_Restrictions {

    public static function init() {
        add_filter('ajax_query_attachments_args', [__CLASS__, 'show_current_user_attachments']);
        add_filter('user_has_cap', [__CLASS__, 'restrict_media_edit_delete'], 10, 4);
    }

    public static function show_current_user_attachments($query) {
        $user_id = get_current_user_id();
        if (current_user_can('service_provider') && !current_user_can('administrator')) {
            $query['author'] = $user_id;
        }
        return $query;
    }

    public static function restrict_media_edit_delete($allcaps, $caps, $args, $user) {
        if (isset($args[2])) {
            $post = get_post($args[2]);

            if ($post && $post->post_type == 'attachment') {
                if ($post->post_author == $user->ID) {
                    // Permitir edição e exclusão para o autor
                    $allcaps['edit_post'] = true;
                    $allcaps['delete_post'] = true;
                } else {
                    // Bloquear edição e exclusão se não for o autor
                    if (current_user_can('service_provider') && !current_user_can('administrator')) {
                        $allcaps['edit_post'] = false;
                        $allcaps['delete_post'] = false;
                    }
                }
            }
        }

        return $allcaps;
    
}
}
RHB_Media_Restrictions::init();
?>

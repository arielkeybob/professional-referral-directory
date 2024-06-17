<?php
defined('ABSPATH') or die('No script kiddies please!');

class RHB_Users {
    protected static $service_provider_caps = [
        'edit_service_provider_service',
        'read_service_provider_service',
        'delete_service_provider_service',
        'edit_service_provider_services',
        'publish_service_provider_services',
        'edit_published_service_provider_services',
        'delete_service_provider_services',
        'delete_published_service_provider_services',
        'delete_posts',
        'delete_published_posts',
    ];

    public static function initialize_user_roles() {
        if (!get_role('service_provider')) {
            add_role(
                'service_provider',
                'Service Provider',
                ['read' => true, 'upload_files' => true]
            );
        }

        $role = get_role('service_provider');
        if ($role) {
            foreach (self::$service_provider_caps as $cap) {
                $role->add_cap($cap);
            }
        }
    }

    public static function cleanup_user_roles() {
        $role = get_role('service_provider');
        if ($role) {
            foreach (self::$service_provider_caps as $cap) {
                $role->remove_cap($cap);
            }
        }
        remove_role('service_provider');
    }

    public static function add_custom_user_profile_fields($user) {
        echo '<h3>' . __('Additional Profile Information', 'referralhub') . '</h3>';

        // Campos visíveis para o service provider
        if (current_user_can('administrator') || in_array('service_provider', (array) $user->roles)) {
            ?>
            <table class="form-table">
                <tr>
                    <th><label for="telefone"><?php _e('Telefone', 'referralhub'); ?></label></th>
                    <td><input type="text" name="telefone" id="telefone" value="<?php echo esc_attr(get_the_author_meta('telefone', $user->ID)); ?>" class="regular-text" /></td>
                </tr>
                <tr>
                    <th><label for="social"><?php _e('Social Media', 'referralhub'); ?></label></th>
                    <td><input type="text" name="social" id="social" value="<?php echo esc_attr(get_the_author_meta('social', $user->ID)); ?>" class="regular-text" /></td>
                </tr>
            </table>
            <?php
        }

        // Campos de comissão visíveis apenas para o administrador
        if (current_user_can('administrator')) {
            $referral_fee_type = get_user_meta($user->ID, 'rhb_referral_fee_type', true);
            $referral_fee_view = get_user_meta($user->ID, 'rhb_referral_fee_view', true);
            $referral_fee_agreement_reached = get_user_meta($user->ID, 'rhb_referral_fee_agreement_reached', true);
            $override_referral_fee = get_user_meta($user->ID, 'rhb_override_referral_fee', true) == 'yes';
            ?>
            <h3><?php _e('Referral Fee Settings', 'referralhub'); ?></h3>
            <table class="form-table">
            <tr>
                <th><label for="override_referral_fee"><?php _e('Override Global Referral Fee Settings', 'referralhub'); ?></label></th>
                <td>
                    <label class="rhb-switch">
                        <input type="checkbox" name="override_referral_fee" id="override_referral_fee" value="yes" class="rhb-toggle-checkbox" <?php checked($override_referral_fee, true); ?> />
                        <span class="rhb-slider"></span>
                    </label>
                </td>
            </tr>

                <tr class="referral_fee_settings" style="display: <?php echo $override_referral_fee ? '' : 'none'; ?>">
                    <th><label for="referral_fee_type"><?php _e('Referral Fee Type', 'referralhub'); ?></label></th>
                    <td>
                        <select id="referral_fee_type" name="referral_fee_type">
                            <option value="view" <?php selected($referral_fee_type, 'view'); ?>><?php _e('Per View', 'referralhub'); ?></option>
                            <option value="agreement_reached" <?php selected($referral_fee_type, 'agreement_reached'); ?>><?php _e('Per Agreement Reached', 'referralhub'); ?></option>
                            <option value="both" <?php selected($referral_fee_type, 'both'); ?>><?php _e('Combination of Both', 'referralhub'); ?></option>
                        </select>
                    </td>
                </tr>
                <tr class="referral_fee_view referral_fee_settings" style="display: <?php echo $override_referral_fee && ($referral_fee_type === 'view' || $referral_fee_type === 'both') ? '' : 'none'; ?>">
                    <th><label for="referral_fee_view"><?php _e('Per View', 'referralhub'); ?></label></th>
                    <td>
                        <input type="text" name="referral_fee_view" id="referral_fee_view" value="<?php echo esc_attr($referral_fee_view); ?>" />
                    </td>
                </tr>
                <tr class="referral_fee_agreement_reached referral_fee_settings" style="display: <?php echo $override_referral_fee && ($referral_fee_type === 'agreement_reached' || $referral_fee_type === 'both') ? '' : 'none'; ?>">
                    <th><label for="referral_fee_agreement_reached"><?php _e('Per Agreement Reached', 'referralhub'); ?></label></th>
                    <td>
                        <input type="text" name="referral_fee_agreement_reached" id="referral_fee_agreement_reached" value="<?php echo esc_attr($referral_fee_agreement_reached); ?>" />
                    </td>
                </tr>
            </table>
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    function toggleReferralFeeSettings() {
                        const override = document.getElementById('override_referral_fee').checked;
                        const type = document.getElementById('referral_fee_type').value;
                        document.querySelectorAll('.referral_fee_settings').forEach(el => el.style.display = override ? '' : 'none');
                        document.querySelector('.referral_fee_view').style.display = (override && (type === 'view' || type === 'both')) ? '' : 'none';
                        document.querySelector('.referral_fee_agreement_reached').style.display = (override && (type === 'agreement_reached' || type === 'both')) ? '' : 'none';
                    }
                    document.getElementById('referral_fee_type').addEventListener('change', toggleReferralFeeSettings);
                    document.getElementById('override_referral_fee').addEventListener('change', toggleReferralFeeSettings);
                    toggleReferralFeeSettings();
                });
            </script>
            <?php
        }
    }

    public static function save_custom_user_profile_fields($user_id) {
        if (!current_user_can('edit_user', $user_id)) {
            return false;
        }

        update_user_meta($user_id, 'telefone', sanitize_text_field($_POST['telefone']));
        update_user_meta($user_id, 'social', sanitize_text_field($_POST['social']));

        // Salva campos de comissão se for administrador
        if (current_user_can('administrator')) {
            update_user_meta($user_id, 'rhb_override_referral_fee', $_POST['override_referral_fee'] ? 'yes' : 'no');
            if ($_POST['override_referral_fee']) {
                update_user_meta($user_id, 'rhb_referral_fee_type', sanitize_text_field($_POST['referral_fee_type']));
                update_user_meta($user_id, 'rhb_referral_fee_view', sanitize_text_field($_POST['referral_fee_view']));
                update_user_meta($user_id, 'rhb_referral_fee_agreement_reached', sanitize_text_field($_POST['referral_fee_agreement_reached']));
            }
        }
    }

    public static function register_hooks() {
        add_action('show_user_profile', [__CLASS__, 'add_custom_user_profile_fields']);
        add_action('edit_user_profile', [__CLASS__, 'add_custom_user_profile_fields']);
        add_action('personal_options_update', [__CLASS__, 'save_custom_user_profile_fields']);
        add_action('edit_user_profile_update', [__CLASS__, 'save_custom_user_profile_fields']);
    }
}

RHB_Users::register_hooks();
?>

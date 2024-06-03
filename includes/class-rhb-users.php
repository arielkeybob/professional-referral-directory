<?php
defined('ABSPATH') or die('No script kiddies please!');

class RHB_Users {

    public static function initialize_user_roles() {
        // Criação e configuração do papel 'service_provider'
        if (!get_role('service_provider')) {
            add_role(
                'service_provider',
                'Service Provider',
                array(
                    'read' => true, // Permite que o usuário leia
                    'upload_files' => true, // Permite que o usuário faça upload de arquivos
                )
            );
        }
        // Adiciona as capacidades ao papel 'service_provider' após a criação do papel.
        $role = get_role('service_provider');
        if ($role) {
            $role->add_cap('edit_services');
            $role->add_cap('edit_published_services');
            $role->add_cap('upload_files');
        }
    }

    public static function cleanup_user_roles() {
        if ($role = get_role('service_provider')) {
            $role->remove_cap('edit_services');
            $role->remove_cap('edit_published_services');
            $role->remove_cap('upload_files');
        }
        remove_role('service_provider');
    }

    public static function add_custom_user_profile_fields($user) {
        if (current_user_can('administrator') && in_array('service_provider', (array) $user->roles)) {
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
                        <input type="checkbox" name="override_referral_fee" id="override_referral_fee" value="yes" <?php checked($override_referral_fee, true); ?> />
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
                    toggleReferralFeeSettings();  // Call on page load to set initial state
                });
            </script>
            <?php
        }
    }

    public static function save_custom_user_profile_fields($user_id) {
        if (!current_user_can('administrator')) {
            return false;
        }
    
        // Verifica se o checkbox 'override_referral_fee' foi marcado e salva a opção
        $override_referral_fee = isset($_POST['override_referral_fee']) ? 'yes' : 'no';
        update_user_meta($user_id, 'rhb_override_referral_fee', $override_referral_fee);
    
        // Salva as outras configurações de Referral Fee somente se o override está ativo
        if ($override_referral_fee === 'yes') {
            if (isset($_POST['referral_fee_type'])) {
                update_user_meta($user_id, 'rhb_referral_fee_type', sanitize_text_field($_POST['referral_fee_type']));
            }
            if (isset($_POST['referral_fee_view'])) {
                update_user_meta($user_id, 'rhb_referral_fee_view', sanitize_text_field($_POST['referral_fee_view']));
            }
            if (isset($_POST['referral_fee_agreement_reached'])) {
                update_user_meta($user_id, 'rhb_referral_fee_agreement_reached', sanitize_text_field($_POST['referral_fee_agreement_reached']));
            }
        }
    }
    

    public static function register_hooks() {
        add_action('show_user_profile', array(__CLASS__, 'add_custom_user_profile_fields'));
        add_action('edit_user_profile', array(__CLASS__, 'add_custom_user_profile_fields'));
        add_action('personal_options_update', array(__CLASS__, 'save_custom_user_profile_fields'));
        add_action('edit_user_profile_update', array(__CLASS__, 'save_custom_user_profile_fields'));
    }
}

RHB_Users::register_hooks();
?>

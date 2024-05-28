<?php
defined('ABSPATH') or die('No script kiddies please!');

class PDR_Users {

    public static function initialize_user_roles() {
        // Criação e configuração do papel 'professional'
        if (!get_role('professional')) {
            add_role(
                'professional',
                'Professional',
                array(
                    'read' => true, // Permite que o usuário leia
                    'upload_files' => true, // Permite que o usuário faça upload de arquivos
                )
            );
        }
        // Adiciona as capacidades ao papel 'professional' após a criação do papel.
        $role = get_role('professional');
        if ($role) {
            $role->add_cap('edit_services');
            $role->add_cap('edit_published_services');
            $role->add_cap('upload_files');
        }
    }

    public static function cleanup_user_roles() {
        if ($role = get_role('professional')) {
            $role->remove_cap('edit_services');
            $role->remove_cap('edit_published_services');
            $role->remove_cap('upload_files');
        }
        remove_role('professional');
    }

    public static function add_custom_user_profile_fields($user) {
        if (current_user_can('administrator') && in_array('professional', (array) $user->roles)) {
            $commission_type = get_user_meta($user->ID, 'pdr_commission_type', true);
            $commission_view = get_user_meta($user->ID, 'pdr_commission_view', true);
            $commission_approval = get_user_meta($user->ID, 'pdr_commission_approval', true);
            $override_commission = get_user_meta($user->ID, 'pdr_override_commission', true) == 'yes';
            ?>
            <h3><?php _e('Configurações de Comissão', 'professional-directory'); ?></h3>
            <table class="form-table">
                <tr>
                    <th><label for="override_commission"><?php _e('Sobrescrever configurações gerais de comissão', 'professional-directory'); ?></label></th>
                    <td>
                        <input type="checkbox" name="override_commission" id="override_commission" value="yes" <?php checked($override_commission, true); ?> />
                    </td>
                </tr>
                <tr class="commission_settings" style="display: <?php echo $override_commission ? '' : 'none'; ?>">
                    <th><label for="commission_type"><?php _e('Tipo de Comissão', 'professional-directory'); ?></label></th>
                    <td>
                        <select id="commission_type" name="commission_type">
                            <option value="view" <?php selected($commission_type, 'view'); ?>><?php _e('Por Visualização', 'professional-directory'); ?></option>
                            <option value="approval" <?php selected($commission_type, 'approval'); ?>><?php _e('Por Pesquisa Aprovada', 'professional-directory'); ?></option>
                            <option value="both" <?php selected($commission_type, 'both'); ?>><?php _e('Combinação das Duas', 'professional-directory'); ?></option>
                        </select>
                    </td>
                </tr>
                <tr class="commission_view commission_settings" style="display: <?php echo $override_commission && ($commission_type === 'view' || $commission_type === 'both') ? '' : 'none'; ?>">
                    <th><label for="commission_view"><?php _e('Comissão por Visualização', 'professional-directory'); ?></label></th>
                    <td>
                        <input type="text" name="commission_view" id="commission_view" value="<?php echo esc_attr($commission_view); ?>" />
                    </td>
                </tr>
                <tr class="commission_approval commission_settings" style="display: <?php echo $override_commission && ($commission_type === 'approval' || $commission_type === 'both') ? '' : 'none'; ?>">
                    <th><label for="commission_approval"><?php _e('Comissão por Pesquisa Aprovada', 'professional-directory'); ?></label></th>
                    <td>
                        <input type="text" name="commission_approval" id="commission_approval" value="<?php echo esc_attr($commission_approval); ?>" />
                    </td>
                </tr>
            </table>
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    function toggleCommissionSettings() {
                        const override = document.getElementById('override_commission').checked;
                        const type = document.getElementById('commission_type').value;
                        document.querySelectorAll('.commission_settings').forEach(el => el.style.display = override ? '' : 'none');
                        document.querySelector('.commission_view').style.display = (override && (type === 'view' || type === 'both')) ? '' : 'none';
                        document.querySelector('.commission_approval').style.display = (override && (type === 'approval' || type === 'both')) ? '' : 'none';
                    }
                    document.getElementById('commission_type').addEventListener('change', toggleCommissionSettings);
                    document.getElementById('override_commission').addEventListener('change', toggleCommissionSettings);
                    toggleCommissionSettings();  // Call on page load to set initial state
                });
            </script>
            <?php
        }
    }

    public static function save_custom_user_profile_fields($user_id) {
        if (!current_user_can('administrator')) {
            return false;
        }

        update_user_meta($user_id, 'pdr_override_commission', isset($_POST['override_commission']) ? 'yes' : 'no');
        if (isset($_POST['commission_type'])) {
            update_user_meta($user_id, 'pdr_commission_type', sanitize_text_field($_POST['commission_type']));
        }
        if (isset($_POST['commission_view'])) {
            update_user_meta($user_id, 'pdr_commission_view', sanitize_text_field($_POST['commission_view']));
        }
        if (isset($_POST['commission_approval'])) {
            update_user_meta($user_id, 'pdr_commission_approval', sanitize_text_field($_POST['commission_approval']));
        }
    }

    public static function register_hooks() {
        add_action('show_user_profile', array(__CLASS__, 'add_custom_user_profile_fields'));
        add_action('edit_user_profile', array(__CLASS__, 'add_custom_user_profile_fields'));
        add_action('personal_options_update', array(__CLASS__, 'save_custom_user_profile_fields'));
        add_action('edit_user_profile_update', array(__CLASS__, 'save_custom_user_profile_fields'));
    }
}

PDR_Users::register_hooks();
?>

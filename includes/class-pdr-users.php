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
                    // Adicione outras capacidades específicas para o papel 'professional' aqui.
                )
            );
        }

        // Adiciona as capacidades ao papel 'professional' após a criação do papel.
        $role = get_role('professional');
        if ($role) {
            // Adicione as capacidades relacionadas ao tipo de post 'service'.
            $role->add_cap('edit_services');
            $role->add_cap('edit_published_services');
            $role->add_cap('upload_files'); // Certifique-se de que a capacidade de upload está incluída
            // Adicione outras capacidades conforme necessário.
        }
    }

    public static function cleanup_user_roles() {
        // Remoção do papel 'professional' e suas capacidades
        if ($role = get_role('professional')) {
            $role->remove_cap('edit_services');
            $role->remove_cap('edit_published_services');
            $role->remove_cap('upload_files'); // Remova a capacidade de upload também
            // Remova outras capacidades adicionadas.
        }
        remove_role('professional');
    }

    public static function add_custom_user_profile_fields($user) {
        // Verifica se o usuário atual é administrador
        if (current_user_can('administrator') && in_array('professional', (array) $user->roles)) {
            $commission_type = get_user_meta($user->ID, 'pdr_commission_type', true);
            $commission_view = get_user_meta($user->ID, 'pdr_commission_view', true);
            $commission_approval = get_user_meta($user->ID, 'pdr_commission_approval', true);
            ?>
            <h3><?php _e('Configurações de Comissão', 'professional-directory'); ?></h3>
            <table class="form-table">
                <tr>
                    <th><label for="commission_type"><?php _e('Tipo de Comissão', 'professional-directory'); ?></label></th>
                    <td>
                        <select id="commission_type" name="commission_type">
                            <option value="view" <?php selected($commission_type, 'view'); ?>><?php _e('Por Visualização', 'professional-directory'); ?></option>
                            <option value="approval" <?php selected($commission_type, 'approval'); ?>><?php _e('Por Pesquisa Aprovada', 'professional-directory'); ?></option>
                            <option value="both" <?php selected($commission_type, 'both'); ?>><?php _e('Combinação das Duas', 'professional-directory'); ?></option>
                        </select>
                    </td>
                </tr>
                <tr class="commission_view">
                    <th><label for="commission_view"><?php _e('Comissão por Visualização', 'professional-directory'); ?></th>
                    <td>
                        <input type="text" name="commission_view" id="commission_view" value="<?php echo esc_attr($commission_view); ?>" />
                    </td>
                </tr>
                <tr class="commission_approval">
                    <th><label for="commission_approval"><?php _e('Comissão por Pesquisa Aprovada', 'professional-directory'); ?></th>
                    <td>
                        <input type="text" name="commission_approval" id="commission_approval" value="<?php echo esc_attr($commission_approval); ?>" />
                    </td>
                </tr>
            </table>
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    function toggleCommissionFields() {
                        const type = document.getElementById('commission_type').value;
                        document.querySelector('.commission_view').style.display = (type === 'view' || type === 'both') ? 'table-row' : 'none';
                        document.querySelector('.commission_approval').style.display = (type === 'approval' || type === 'both') ? 'table-row' : 'none';
                    }

                    document.getElementById('commission_type').addEventListener('change', toggleCommissionFields);
                    toggleCommissionFields();
                });
            </script>
            <?php
        }
    }

    public static function save_custom_user_profile_fields($user_id) {
        // Código para salvar os campos personalizados
        if (!current_user_can('administrator')) {
            return false;
        }

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

    public static function hideAdminColorSchemeForProfessionals() {
        $user = wp_get_current_user();
        if (in_array('professional', (array)$user->roles)) {
            echo '<style>tr.user-admin-color-wrap { display: none; }</style>';
        }
    }

    public static function register_hooks() {
        // Registra os hooks para adicionar e salvar campos personalizados
        add_action('show_user_profile', array(__CLASS__, 'add_custom_user_profile_fields'));
        add_action('edit_user_profile', array(__CLASS__, 'add_custom_user_profile_fields'));

        add_action('personal_options_update', array(__CLASS__, 'save_custom_user_profile_fields'));
        add_action('edit_user_profile_update', array(__CLASS__, 'save_custom_user_profile_fields'));
        // Oculta a opção de esquema de cores para usuários "professional"
        add_action('admin_head', array(__CLASS__, 'hideAdminColorSchemeForProfessionals'));
    }
}

// Chama o método register_hooks na inicialização do plugin
PDR_Users::register_hooks();
?>

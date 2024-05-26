<?php
defined('ABSPATH') or die('No script kiddies please!');

function pdr_commissions_settings_page() {
    // Verifique se o formulário foi enviado
    if (isset($_POST['pdr_commission_settings_save'])) {
        check_admin_referer('pdr_commission_settings_nonce');

        // Salve as configurações gerais
        $commission_type = sanitize_text_field($_POST['commission_type']);
        update_option('pdr_commission_type', $commission_type);

        $general_commission_view = sanitize_text_field($_POST['general_commission_view']);
        update_option('pdr_general_commission_view', $general_commission_view);

        $general_commission_approval = sanitize_text_field($_POST['general_commission_approval']);
        update_option('pdr_general_commission_approval', $general_commission_approval);

        echo '<div class="updated"><p>Configurações salvas.</p></div>';
    }

    // Obtenha os valores atuais das configurações
    $commission_type = get_option('pdr_commission_type', 'view');
    $general_commission_view = get_option('pdr_general_commission_view', '');
    $general_commission_approval = get_option('pdr_general_commission_approval', '');
    ?>
    <div class="wrap">
        <h1><?php _e('Configurações de Comissões', 'professional-directory'); ?></h1>
        <form method="post" action="">
            <?php wp_nonce_field('pdr_commission_settings_nonce'); ?>
            <h2><?php _e('Configurações Gerais', 'professional-directory'); ?></h2>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row"><?php _e('Tipo de Comissão', 'professional-directory'); ?></th>
                    <td>
                        <select id="commission_type" name="commission_type">
                            <option value="view" <?php selected($commission_type, 'view'); ?>><?php _e('Por Visualização', 'professional-directory'); ?></option>
                            <option value="approval" <?php selected($commission_type, 'approval'); ?>><?php _e('Por Pesquisa Aprovada', 'professional-directory'); ?></option>
                            <option value="both" <?php selected($commission_type, 'both'); ?>><?php _e('Combinação das Duas', 'professional-directory'); ?></option>
                        </select>
                    </td>
                </tr>
                <tr valign="top" class="commission_view">
                    <th scope="row"><?php _e('Comissão Geral por Visualização', 'professional-directory'); ?></th>
                    <td>
                        <input type="text" name="general_commission_view" value="<?php echo esc_attr($general_commission_view); ?>" />
                        <p class="description"><?php _e('Defina a comissão geral por visualização de serviço.', 'professional-directory'); ?></p>
                    </td>
                </tr>
                <tr valign="top" class="commission_approval">
                    <th scope="row"><?php _e('Comissão Geral por Pesquisa Aprovada', 'professional-directory'); ?></th>
                    <td>
                        <input type="text" name="general_commission_approval" value="<?php echo esc_attr($general_commission_approval); ?>" />
                        <p class="description"><?php _e('Defina a comissão geral por pesquisa aprovada.', 'professional-directory'); ?></p>
                    </td>
                </tr>
            </table>
            <?php submit_button(__('Salvar Configurações', 'professional-directory'), 'primary', 'pdr_commission_settings_save'); ?>
        </form>
    </div>
    <style>
        .wrap h1, .wrap h2 {
            margin-bottom: 20px;
        }
        .form-table th {
            width: 200px;
        }
        .commission_view, .commission_approval {
            display: none;
        }
    </style>
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
?>

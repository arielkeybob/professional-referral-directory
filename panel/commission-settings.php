<?php
defined('ABSPATH') or die('No script kiddies please!');

// Função para registrar e definir as configurações
function pdr_register_commission_settings() {
    // Registra as configurações com sanitização adequada
    register_setting('pdr_commission_options', 'pdr_referral_fee_type', 'sanitize_text_field');
    register_setting('pdr_commission_options', 'pdr_general_commission_view', 'sanitize_text_field');
    register_setting('pdr_commission_options', 'pdr_general_commission_approval', 'sanitize_text_field');
}

// Adiciona a função de registro no hook admin_init
add_action('admin_init', 'pdr_register_commission_settings');

function pdr_commissions_settings_page() {
    ?>
    <div class="wrap">
        <h1><?php echo esc_html__('Configurações de Comissões', 'professional-directory'); ?></h1>
        <form method="post" action="options.php">
            <?php
            settings_fields('pdr_commission_options');  // Define o grupo de opções que essa página vai editar
            do_settings_sections('pdr_commission_options');  // Imprime as seções e seus campos
            // Obtenha os valores atuais das configurações
            $commission_type = get_option('pdr_referral_fee_type', 'view');
            $general_commission_view = get_option('pdr_general_commission_view', '');
            $general_commission_approval = get_option('pdr_general_commission_approval', '');
            ?>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row"><?php echo esc_html__('Tipo de Comissão', 'professional-directory'); ?></th>
                    <td>
                        <select id="commission_type" name="pdr_referral_fee_type">
                            <option value="view" <?php selected($commission_type, 'view'); ?>><?php echo esc_html__('Por Visualização', 'professional-directory'); ?></option>
                            <option value="approval" <?php selected($commission_type, 'approval'); ?>><?php echo esc_html__('Por inquiry Aprovada', 'professional-directory'); ?></option>
                            <option value="both" <?php selected($commission_type, 'both'); ?>><?php echo esc_html__('Combinação das Duas', 'professional-directory'); ?></option>
                        </select>
                    </td>
                </tr>
                <tr valign="top" class="commission_view">
                    <th scope="row"><?php echo esc_html__('Comissão Geral por Visualização', 'professional-directory'); ?></th>
                    <td>
                        <input type="text" name="pdr_general_commission_view" value="<?php echo esc_attr($general_commission_view); ?>" />
                        <p class="description"><?php echo esc_html__('Defina a comissão geral por visualização de serviço.', 'professional-directory'); ?></p>
                    </td>
                </tr>
                <tr valign="top" class="commission_approval">
                    <th scope="row"><?php echo esc_html__('Comissão Geral por inquiry Aprovada', 'professional-directory'); ?></th>
                    <td>
                        <input type="text" name="pdr_general_commission_approval" value="<?php echo esc_attr($general_commission_approval); ?>" />
                        <p class="description"><?php echo esc_html__('Defina a comissão geral por inquiry aprovada.', 'professional-directory'); ?></p>
                    </td>
                </tr>
            </table>
            <?php submit_button(__('Salvar Configurações', 'professional-directory'), 'primary', 'submit'); ?>
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
            toggleCommissionFields();  // Garante que os campos corretos sejam mostrados inicialmente
        });
    </script>
    <?php
}
?>

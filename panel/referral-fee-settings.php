<?php
defined('ABSPATH') or die('No script kiddies please!');

// Função para registrar e definir as configurações
function pdr_register_referral_fee_settings() {
    // Registra as configurações com sanitização adequada
    register_setting('pdr_referral_fee_options', 'pdr_referral_fee_type', 'sanitize_text_field');
    register_setting('pdr_referral_fee_options', 'pdr_general_referral_fee_view', 'sanitize_text_field');
    register_setting('pdr_referral_fee_options', 'pdr_general_referral_fee_approval', 'sanitize_text_field');
}

// Adiciona a função de registro no hook admin_init
add_action('admin_init', 'pdr_register_referral_fee_settings');

function pdr_referral_fees_settings_page() {
    ?>
    <div class="wrap">
        <h1><?php echo esc_html__('Referral Fee Settings', 'professionaldirectory'); ?></h1>
        <form method="post" action="options.php">
            <?php
            settings_fields('pdr_referral_fee_options');  // Define o grupo de opções que essa página vai editar
            do_settings_sections('pdr_referral_fee_options');  // Imprime as seções e seus campos
            // Obtenha os valores atuais das configurações
            $referral_fee_type = get_option('pdr_referral_fee_type', 'view');
            $general_referral_fee_view = get_option('pdr_general_referral_fee_view', '');
            $general_referral_fee_approval = get_option('pdr_general_referral_fee_approval', '');
            ?>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row"><?php echo esc_html__('Referral Fee Type', 'professionaldirectory'); ?></th>
                    <td>
                        <select id="referral_fee_type" name="pdr_referral_fee_type">
                            <option value="view" <?php selected($referral_fee_type, 'view'); ?>><?php echo esc_html__('Por Visualização', 'professionaldirectory'); ?></option>
                            <option value="approval" <?php selected($referral_fee_type, 'approval'); ?>><?php echo esc_html__('Por inquiry Aprovada', 'professionaldirectory'); ?></option>
                            <option value="both" <?php selected($referral_fee_type, 'both'); ?>><?php echo esc_html__('Combinação das Duas', 'professionaldirectory'); ?></option>
                        </select>
                    </td>
                </tr>
                <tr valign="top" class="referral_fee_view">
                    <th scope="row"><?php echo esc_html__('Per View', 'professionaldirectory'); ?></th>
                    <td>
                        <input type="text" name="pdr_general_referral_fee_view" value="<?php echo esc_attr($general_referral_fee_view); ?>" />
                        <p class="description"><?php echo esc_html__('Defina a Referral Fee geral por visualização de serviço.', 'professionaldirectory'); ?></p>
                    </td>
                </tr>
                <tr valign="top" class="referral_fee_approval">
                    <th scope="row"><?php echo esc_html__('Per Approved Inquiry', 'professionaldirectory'); ?></th>
                    <td>
                        <input type="text" name="pdr_general_referral_fee_approval" value="<?php echo esc_attr($general_referral_fee_approval); ?>" />
                        <p class="description"><?php echo esc_html__('Defina a Referral Fee geral por inquiry aprovada.', 'professionaldirectory'); ?></p>
                    </td>
                </tr>
            </table>
            <?php submit_button(__('Salvar Configurações', 'professionaldirectory'), 'primary', 'submit'); ?>
        </form>
    </div>
    <style>
        .wrap h1, .wrap h2 {
            margin-bottom: 20px;
        }
        .form-table th {
            width: 200px;
        }
        .referral_fee_view, .referral_fee_approval {
            display: none;
        }
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            function toggleReferralFeeFields() {
                const type = document.getElementById('referral_fee_type').value;
                document.querySelector('.referral_fee_view').style.display = (type === 'view' || type === 'both') ? 'table-row' : 'none';
                document.querySelector('.referral_fee_approval').style.display = (type === 'approval' || type === 'both') ? 'table-row' : 'none';
            }
            document.getElementById('referral_fee_type').addEventListener('change', toggleReferralFeeFields);
            toggleReferralFeeFields();  // Garante que os campos corretos sejam mostrados inicialmente
        });
    </script>
    <?php
}
?>

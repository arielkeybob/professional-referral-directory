<?php
defined('ABSPATH') or die('No script kiddies please!');

// Função para registrar e definir as configurações
function rhb_register_referral_fee_settings() {
    register_setting('rhb_referral_fee_options', 'rhb_settings', 'rhb_sanitize_settings');
}

function rhb_sanitize_settings($input) {
    $sanitized_input = array();
    
    if (isset($input['rhb_referral_fee_type'])) {
        $sanitized_input['rhb_referral_fee_type'] = sanitize_text_field($input['rhb_referral_fee_type']);
    }
    
    if (isset($input['rhb_general_referral_fee_view'])) {
        $sanitized_input['rhb_general_referral_fee_view'] = sanitize_text_field($input['rhb_general_referral_fee_view']);
    }
    
    if (isset($input['rhb_general_referral_fee_agreement_reached'])) {
        $sanitized_input['rhb_general_referral_fee_agreement_reached'] = sanitize_text_field($input['rhb_general_referral_fee_agreement_reached']);
    }
    
    return $sanitized_input;
}

add_action('admin_init', 'rhb_register_referral_fee_settings');

function rhb_referral_fees_settings_page() {
    ?>
    <div class="wrap">
        <h1><?php echo esc_html__('Referral Fee Settings', 'referralhub'); ?></h1>
        <form method="post" action="options.php">
            <?php
            settings_fields('rhb_referral_fee_options');  // Define o grupo de opções que essa página vai editar
            do_settings_sections('rhb_referral_fee_options');  // Imprime as seções e seus campos
            // Obtenha os valores atuais das configurações
            $options = get_option('rhb_settings', []);
            $referral_fee_type = isset($options['rhb_referral_fee_type']) ? $options['rhb_referral_fee_type'] : 'view';
            $general_referral_fee_view = isset($options['rhb_general_referral_fee_view']) ? $options['rhb_general_referral_fee_view'] : '';
            $general_referral_fee_agreement_reached = isset($options['rhb_general_referral_fee_agreement_reached']) ? $options['rhb_general_referral_fee_agreement_reached'] : '';
            ?>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row"><?php echo esc_html__('Referral Fee Type', 'referralhub'); ?></th>
                    <td>
                        <select id="referral_fee_type" name="rhb_settings[rhb_referral_fee_type]">
                            <option value="view" <?php selected($referral_fee_type, 'view'); ?>><?php echo esc_html__('Por Visualização', 'referralhub'); ?></option>
                            <option value="agreement_reached" <?php selected($referral_fee_type, 'agreement_reached'); ?>><?php echo esc_html__('Por Agreement Reached', 'referralhub'); ?></option>
                            <option value="both" <?php selected($referral_fee_type, 'both'); ?>><?php echo esc_html__('Combinação das Duas', 'referralhub'); ?></option>
                        </select>
                    </td>
                </tr>
                <tr valign="top" class="referral_fee_view">
                    <th scope="row"><?php echo esc_html__('Per View', 'referralhub'); ?></th>
                    <td>
                        <input type="text" name="rhb_settings[rhb_general_referral_fee_view]" value="<?php echo esc_attr($general_referral_fee_view); ?>" />
                        <p class="description"><?php echo esc_html__('Defina a Referral Fee geral por visualização de serviço.', 'referralhub'); ?></p>
                    </td>
                </tr>
                <tr valign="top" class="referral_fee_agreement_reached">
                    <th scope="row"><?php echo esc_html__('Per Agreement Reached', 'referralhub'); ?></th>
                    <td>
                        <input type="text" name="rhb_settings[rhb_general_referral_fee_agreement_reached]" value="<?php echo esc_attr($general_referral_fee_agreement_reached); ?>" />
                        <p class="description"><?php echo esc_html__('Defina a Referral Fee geral por inquiry aprovada.', 'referralhub'); ?></p>
                    </td>
                </tr>
            </table>
            <?php submit_button(__('Salvar Configurações', 'referralhub'), 'primary', 'submit'); ?>
        </form>
    </div>
    <style>
        .wrap h1, .wrap h2 {
            margin-bottom: 20px;
        }
        .form-table th {
            width: 200px;
        }
        .referral_fee_view, .referral_fee_agreement_reached {
            display: none;
        }
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            function toggleReferralFeeFields() {
                const type = document.getElementById('referral_fee_type').value;
                document.querySelector('.referral_fee_view').style.display = (type === 'view' || type === 'both') ? 'table-row' : 'none';
                document.querySelector('.referral_fee_agreement_reached').style.display = (type === 'agreement_reached' || type === 'both') ? 'table-row' : 'none';
            }
            document.getElementById('referral_fee_type').addEventListener('change', toggleReferralFeeFields);
            toggleReferralFeeFields();  // Garante que os campos corretos sejam mostrados inicialmente
        });
    </script>
    <?php
}
?>

<?php if ($provider_data) : ?>
    <h1>Detalhes do Provider: <?php echo esc_html($provider_data->display_name); ?></h1>
    <table>
        <thead>
            <tr>
                <th>Data da Inquiry</th>
                <th>Tipo de Serviço</th>
                <th>Taxa por Visualização</th>
                <th>Taxa por Acordo</th>
                <th>Total de Taxas</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $unpaid_fees = get_provider_unpaid_fees_details($provider_data->ID);
            if (!empty($unpaid_fees)) :
                foreach ($unpaid_fees as $fee) : ?>
                    <tr>
                        <td><?php echo date('d/m/Y', strtotime($fee->inquiry_date)); ?></td>
                        <td><?php echo esc_html($fee->service_type); ?></td>
                        <td><?php echo esc_html($fee->referral_fee_value_view); ?></td>
                        <td><?php echo esc_html($fee->referral_fee_value_agreement_reached); ?></td>
                        <td><?php echo esc_html($fee->total_fee); ?></td>
                    </tr>
                <?php endforeach; 
            else : ?>
                <tr>
                    <td colspan="5">Nenhuma taxa pendente encontrada.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
<?php else : ?>
    <p>Provider não encontrado.</p>
<?php endif; ?>

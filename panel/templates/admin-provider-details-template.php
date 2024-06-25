<?php
defined('ABSPATH') or die('No script kiddies please!');

if ($provider_data) :
    $unpaid_fees = get_provider_unpaid_fees_details($provider_data->ID);
    $invoices = get_provider_invoices($provider_data->ID);
    ?>
    <h1>Detalhes do Provider: <?php echo esc_html($provider_data->display_name); ?></h1>
    
    <h2>Invoices Emitidos</h2>
    <table>
        <thead>
            <tr>
                <th>ID do Invoice</th>
                <th>Total</th>
                <th>Pago</th>
                <th>Data</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($invoices)) : ?>
                <?php foreach ($invoices as $invoice) : ?>
                    <tr>
                        <td><?php echo esc_html($invoice->invoice_id); ?></td>
                        <td><?php echo esc_html(number_format($invoice->total, 2, '.', ',')); ?></td>
                        <td><?php echo $invoice->is_paid ? 'Sim' : 'Não'; ?></td>
                        <td><?php echo date('d/m/Y', strtotime($invoice->created_at)); ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else : ?>
                <tr>
                    <td colspan="4">Nenhum invoice encontrado.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <h2>Inquiries Não Faturados</h2>
    <form id="invoice-form">
        <input type="hidden" name="action" value="create_invoice">
        <input type="hidden" name="provider_id" value="<?php echo esc_attr($provider_data->ID); ?>">
        <table>
            <thead>
                <tr>
                    <th>Selecionar</th>
                    <th>Data da Inquiry</th>
                    <th>Tipo de Serviço</th>
                    <th>Taxa por Visualização</th>
                    <th>Taxa por Acordo</th>
                    <th>Total de Taxas</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($unpaid_fees)) : ?>
                    <?php foreach ($unpaid_fees as $fee) : ?>
                        <tr>
                            <td><input type="checkbox" class="inquiry-checkbox" name="inquiry_ids[]" value="<?php echo esc_attr($fee->id); ?>"></td>
                            <td><?php echo date('d/m/Y', strtotime($fee->inquiry_date)); ?></td>
                            <td><?php echo esc_html($fee->service_type); ?></td>
                            <td><?php echo esc_html($fee->referral_fee_value_view); ?></td>
                            <td><?php echo esc_html($fee->referral_fee_value_agreement_reached); ?></td>
                            <td><?php echo esc_html($fee->total_fee); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="6">Nenhuma taxa pendente encontrada.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
        <button type="button" id="create-invoice">Gerar Invoice</button>
    </form>
    <script>
    document.getElementById('create-invoice').addEventListener('click', function() {
        var form = document.getElementById('invoice-form');
        var data = new FormData(form);

        fetch('<?php echo admin_url('admin-ajax.php'); ?>', {
            method: 'POST',
            credentials: 'same-origin',
            body: data
        }).then(response => response.json()).then(data => {
            if (data.success) {
                alert('Invoice criada com sucesso!');
                // Opcional: atualize a página ou ajuste a UI conforme necessário
            } else {
                alert('Erro: ' + data.data.message);
            }
        }).catch(error => console.error('Erro ao criar invoice:', error));
    });
    </script>
<?php else : ?>
    <p>Provider não encontrado.</p>
<?php endif; ?>

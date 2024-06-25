<?php

defined('ABSPATH') or die('No script kiddies please!');

// Obtém os dados do provider, se um ID for fornecido
$provider_id = isset($_GET['provider_id']) ? intval($_GET['provider_id']) : null;
$invoice_id = isset($_GET['invoice_id']) ? intval($_GET['invoice_id']) : null;

$provider_details = $provider_id ? get_provider_details($provider_id) : null;
$invoice_details = $invoice_id ? get_invoice_details($invoice_id) : null;

// Para preenchimento automático dos campos, se estiver editando
$items = $invoice_details ? get_invoice_items($invoice_details->id) : [];

// Define o modo de visualização
$is_edit_mode = !empty($invoice_id);

// Prepara dados para o formulário
$customer_name = $is_edit_mode ? $provider_details->display_name : '';
$invoice_number = $is_edit_mode ? $invoice_details->invoice_number : 'INV-' . time();
$invoice_date = $is_edit_mode ? $invoice_details->invoice_date : date('Y-m-d');
$total_amount = $is_edit_mode ? $invoice_details->total : 0.00;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $is_edit_mode ? 'Edit Invoice' : 'New Invoice'; ?></title>
</head>
<body>
    <h1><?php echo $is_edit_mode ? 'Edit Invoice' : 'New Invoice'; ?></h1>
    <form action="<?php echo admin_url('admin-post.php'); ?>" method="post">
        <input type="hidden" name="action" value="handle_invoice_save">
        <input type="hidden" name="invoice_id" value="<?php echo esc_attr($invoice_id); ?>">
        <input type="hidden" name="provider_id" value="<?php echo esc_attr($provider_id); ?>">

        <label for="customer_name">Customer Name*</label>
        <input type="text" id="customer_name" name="customer_name" value="<?php echo esc_attr($customer_name); ?>" required>

        <label for="invoice_number">Invoice#</label>
        <input type="text" id="invoice_number" name="invoice_number" value="<?php echo esc_attr($invoice_number); ?>" readonly>

        <label for="invoice_date">Invoice Date*</label>
        <input type="date" id="invoice_date" name="invoice_date" value="<?php echo esc_attr($invoice_date); ?>" required>

        <h2>Item Details</h2>
        <table>
            <thead>
                <tr>
                    <th>Item</th>
                    <th>Quantity</th>
                    <th>Rate</th>
                    <th>Tax</th>
                    <th>Amount</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($items as $item) : ?>
                    <tr>
                        <td><input type="text" name="item_name[]" value="<?php echo esc_html($item->name); ?>"></td>
                        <td><input type="number" name="item_quantity[]" value="<?php echo esc_attr($item->quantity); ?>"></td>
                        <td><input type="text" name="item_rate[]" value="<?php echo esc_attr($item->rate); ?>"></td>
                        <td><select name="item_tax[]"><option value="0">Select Tax</option></select></td>
                        <td><?php echo number_format($item->amount, 2); ?></td>
                    </tr>
                <?php endforeach; ?>
                <tr>
                    <td><button type="button" onclick="addNewItemRow();">Add New Row</button></td>
                </tr>
            </tbody>
        </table>

        <label for="customer_notes">Customer Notes</label>
        <textarea id="customer_notes" name="customer_notes">Thanks for your business.</textarea>

        <label for="terms_conditions">Terms & Conditions</label>
        <textarea id="terms_conditions" name="terms_conditions">Payment is due within 30 days.</textarea>

        <button type="submit"><?php echo $is_edit_mode ? 'Update' : 'Save'; ?></button>
        <button type="button" onclick="cancelInvoice();">Cancel</button>
    </form>

    <script>
    function addNewItemRow() {
        // JavaScript para adicionar uma nova linha de item
    }

    function cancelInvoice() {
        // JavaScript para cancelar ou fechar o formulário
    }
    </script>
</body>
</html>

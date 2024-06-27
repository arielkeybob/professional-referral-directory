<?php
defined('ABSPATH') or die('No script kiddies please!');

$invoice_id = isset($_GET['invoice_id']) ? intval($_GET['invoice_id']) : null;
$invoice_details = $invoice_id ? get_invoice_details($invoice_id) : null;

$is_edit_mode = !empty($invoice_id);
$customer_name = $is_edit_mode ? $invoice_details->provider_name : '';
$invoice_date = $is_edit_mode ? $invoice_details->invoice_date : date('Y-m-d');
$total_amount = $is_edit_mode ? $invoice_details->total : 0.00;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo $is_edit_mode ? 'Edit Invoice' : 'Create New Invoice'; ?></title>
</head>
<body>
    <h1><?php echo $is_edit_mode ? 'Edit Invoice' : 'Create New Invoice'; ?></h1>
    <form action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="post">
        <input type="hidden" name="action" value="save_invoice">
        <input type="hidden" name="invoice_id" value="<?php echo esc_attr($invoice_id); ?>">

        <label for="customer_name">Customer Name:</label>
        <input type="text" id="customer_name" name="customer_name" value="<?php echo esc_attr($customer_name); ?>" <?php echo $is_edit_mode ? 'readonly' : ''; ?> required>

        <label for="invoice_date">Invoice Date:</label>
        <input type="date" id="invoice_date" name="invoice_date" value="<?php echo esc_attr(date('Y-m-d', strtotime($invoice_date))); ?>" required>

        <label for="total_amount">Total Amount:</label>
        <input type="text" id="total_amount" name="total_amount" value="<?php echo esc_attr($total_amount); ?>" required>

        <button type="submit">Save</button>
    </form>
</body>
</html>

<?php
    defined('ABSPATH') or die('No script kiddies please!');
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" type="text/css" href="<?php echo plugins_url('/css/dashboard-style-admin.css', dirname(__FILE__)); ?>">
</head>
<body>
    <div id="dashboard-container">
        <h1><?php _e('Admin Dashboard', 'referralhub'); ?></h1>
        <div>
            <label for="period-selector"><?php _e('Select Period:', 'referralhub'); ?></label>
            <select id="period-selector">
                <option value="today"><?php _e('Today', 'referralhub'); ?></option>
                <option value="last_week"><?php _e('Last Week', 'referralhub'); ?></option>
                <option value="last_month"><?php _e('Last Month', 'referralhub'); ?></option>
                <option value="this_year"><?php _e('This Year', 'referralhub'); ?></option>
                <option value="custom"><?php _e('Custom', 'referralhub'); ?></option>
            </select>
            <div id="custom-period" style="display:none;">
                <input type="date" id="start-date">
                <input type="date" id="end-date">
            </div>
            <button id="load-data"><?php _e('Load Data', 'referralhub'); ?></button>
        </div>
        <div>
            <input type="checkbox" id="show-services-without-Inquiry">
            <label for="show-services-without-Inquiry"><?php _e('Also show services without inquiries in the period', 'referralhub'); ?></label>
        </div>
        <table id="services-data">
            <thead>
                <tr>
                    <th><?php _e('Service Name', 'referralhub'); ?></th>
                    <th><?php _e('Number of Inquiries', 'referralhub'); ?></th>
                    <th><?php _e('Author Name', 'referralhub'); ?></th>
                    <th><?php _e('Date of Last Inquiry', 'referralhub'); ?></th>
                </tr>
            </thead>
            <tbody>
                <!-- Service data will be inserted here by JavaScript -->
            </tbody>
        </table>
    </div>
</body>
</html>

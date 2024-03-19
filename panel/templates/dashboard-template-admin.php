<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" type="text/css" href="<?php echo plugins_url('/css/dashboard-style-admin.css', dirname(__FILE__)); ?>">
</head>
<body>
    <div id="dashboard-container">
        <h1><?php _e('Admin Dashboard', 'professionaldirectory'); ?></h1>
        <div>
            <label for="period-selector"><?php _e('Select Period:', 'professionaldirectory'); ?></label>
            <select id="period-selector">
                <option value="today"><?php _e('Today', 'professionaldirectory'); ?></option>
                <option value="last_week"><?php _e('Last Week', 'professionaldirectory'); ?></option>
                <option value="last_month"><?php _e('Last Month', 'professionaldirectory'); ?></option>
                <option value="this_year"><?php _e('This Year', 'professionaldirectory'); ?></option>
                <option value="custom"><?php _e('Custom', 'professionaldirectory'); ?></option>
            </select>
            <div id="custom-period" style="display:none;">
                <input type="date" id="start-date">
                <input type="date" id="end-date">
            </div>
            <button id="load-data"><?php _e('Load Data', 'professionaldirectory'); ?></button>
        </div>
        <div>
            <input type="checkbox" id="show-services-without-search">
            <label for="show-services-without-search"><?php _e('Also show services without searches in the period', 'professionaldirectory'); ?></label>
        </div>
        <table id="services-data">
            <thead>
                <tr>
                    <th><?php _e('Service Name', 'professionaldirectory'); ?></th>
                    <th><?php _e('Number of Searches', 'professionaldirectory'); ?></th>
                    <th><?php _e('Author Name', 'professionaldirectory'); ?></th>
                    <th><?php _e('Date of Last Search', 'professionaldirectory'); ?></th>
                </tr>
            </thead>
            <tbody>
                <!-- Service data will be inserted here by JavaScript -->
            </tbody>
        </table>
    </div>
    <!-- No final do body -->
<script src="path/to/materialize/js/materialize.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var elems = document.querySelectorAll('select');
    var instances = M.FormSelect.init(elems);
});
</script>

</body>
</html>

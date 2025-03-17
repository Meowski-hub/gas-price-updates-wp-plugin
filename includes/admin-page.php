<?php
if (!defined('ABSPATH')) {
    exit;
}

function gpu_admin_page() {
    if (isset($_POST['submit_csv']) && isset($_FILES['csv_file'])) {
        gpu_handle_csv_upload();
    }
    ?>
    <div class="wrap">
        <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
        
        <div class="gpu-admin-container">
            <div class="gpu-upload-section">
                <h2><?php _e('Upload CSV File', 'gas-price-updates'); ?></h2>
                <form method="post" enctype="multipart/form-data">
                    <input type="file" name="csv_file" accept=".csv" required>
                    <?php wp_nonce_field('gpu_csv_upload', 'gpu_csv_nonce'); ?>
                    <input type="submit" name="submit_csv" class="button button-primary" value="<?php _e('Upload', 'gas-price-updates'); ?>">
                </form>
            </div>
            
            <div class="gpu-preview-section">
                <h2><?php _e('Current Gas Prices', 'gas-price-updates'); ?></h2>
                <?php gpu_display_prices_table(); ?>
            </div>
        </div>
        
        <div class="gpu-shortcode-info">
            <h3><?php _e('Shortcode', 'gas-price-updates'); ?></h3>
            <p><?php _e('Use this shortcode to display the gas prices table on any page or post:', 'gas-price-updates'); ?></p>
            <code>[gas_prices_table]</code>
        </div>
    </div>
    <?php
}

function gpu_display_prices_table() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'gas_prices';
    
    $prices = $wpdb->get_results("SELECT * FROM $table_name ORDER BY price ASC");
    
    if (empty($prices)) {
        echo '<p>' . __('No gas prices found. Upload a CSV file to get started.', 'gas-price-updates') . '</p>';
        return;
    }
    
    ?>
    <table class="wp-list-table widefat fixed striped">
        <thead>
            <tr>
                <th><?php _e('Station', 'gas-price-updates'); ?></th>
                <th><?php _e('Price', 'gas-price-updates'); ?></th>
                <th><?php _e('Address', 'gas-price-updates'); ?></th>
                <th><?php _e('Last Updated', 'gas-price-updates'); ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($prices as $price): ?>
            <tr>
                <td><?php echo esc_html($price->station_name); ?></td>
                <td><?php echo esc_html(number_format($price->price, 2)); ?></td>
                <td><?php echo esc_html($price->address); ?></td>
                <td><?php echo esc_html($price->last_updated); ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php
}
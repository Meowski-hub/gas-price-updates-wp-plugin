<?php
if (!defined('ABSPATH')) {
    exit;
}

// Register shortcode
add_shortcode('gas_prices_table', 'gpu_prices_table_shortcode');

function gpu_prices_table_shortcode($atts) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'gas_prices';
    
    $prices = $wpdb->get_results("SELECT * FROM $table_name ORDER BY price ASC");
    
    if (empty($prices)) {
        return '<p>' . __('No gas prices available.', 'gas-price-updates') . '</p>';
    }
    
    ob_start();
    ?>
    <div class="gpu-prices-container">
        <h2><?php _e('Current Gas Prices', 'gas-price-updates'); ?></h2>
        <div class="gpu-prices-table">
            <table>
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
                        <td><?php echo esc_html(date('F j, Y g:i a', strtotime($price->last_updated))); ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php
    return ob_get_clean();
}
<?php
if (!defined('ABSPATH')) {
    exit;
}

function gpu_handle_csv_upload() {
    if (!isset($_POST['gpu_csv_nonce']) || !wp_verify_nonce($_POST['gpu_csv_nonce'], 'gpu_csv_upload')) {
        wp_die(__('Security check failed', 'gas-price-updates'));
    }
    
    $file = $_FILES['csv_file'];
    $allowed_types = array('text/csv', 'application/csv');
    
    if (!in_array($file['type'], $allowed_types)) {
        add_settings_error(
            'gpu_messages',
            'gpu_error',
            __('Please upload a valid CSV file', 'gas-price-updates'),
            'error'
        );
        return;
    }
    
    $handle = fopen($file['tmp_name'], 'r');
    $header = fgetcsv($handle);
    
    // Verify CSV structure
    $required_columns = array('station_name', 'price', 'address');
    if (count(array_intersect($required_columns, $header)) !== count($required_columns)) {
        add_settings_error(
            'gpu_messages',
            'gpu_error',
            __('CSV file must contain station_name, price, and address columns', 'gas-price-updates'),
            'error'
        );
        fclose($handle);
        return;
    }
    
    global $wpdb;
    $table_name = $wpdb->prefix . 'gas_prices';
    $success_count = 0;
    
    // Clear existing data
    $wpdb->query("TRUNCATE TABLE $table_name");
    
    while (($data = fgetcsv($handle)) !== FALSE) {
        $wpdb->insert(
            $table_name,
            array(
                'station_name' => sanitize_text_field($data[array_search('station_name', $header)]),
                'price' => floatval($data[array_search('price', $header)]),
                'address' => sanitize_text_field($data[array_search('address', $header)]),
                'last_updated' => current_time('mysql')
            ),
            array('%s', '%f', '%s', '%s')
        );
        
        if ($wpdb->last_error === '') {
            $success_count++;
        }
    }
    
    fclose($handle);
    
    add_settings_error(
        'gpu_messages',
        'gpu_success',
        sprintf(__('Successfully imported %d gas price records', 'gas-price-updates'), $success_count),
        'success'
    );
}
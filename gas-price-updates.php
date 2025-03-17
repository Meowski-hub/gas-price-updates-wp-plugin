<?php
/**
 * Plugin Name: Gas Price Updates
 * Plugin URI: https://github.com/Meowski-hub/gas-price-updates-wp-plugin
 * Description: A WordPress plugin for displaying and managing gas price updates with CSV upload functionality
 * Version: 1.0.0
 * Author: Your Name
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: gas-price-updates
 */

if (!defined('ABSPATH')) {
    exit;
}

// Plugin activation hook
register_activation_hook(__FILE__, 'gpu_activate_plugin');

function gpu_activate_plugin() {
    global $wpdb;
    
    $table_name = $wpdb->prefix . 'gas_prices';
    
    $charset_collate = $wpdb->get_charset_collate();
    
    $sql = "CREATE TABLE IF NOT EXISTS $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        station_name varchar(100) NOT NULL,
        price decimal(10,2) NOT NULL,
        address varchar(255) NOT NULL,
        last_updated datetime DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY  (id)
    ) $charset_collate;";
    
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}

// Add admin menu
add_action('admin_menu', 'gpu_add_admin_menu');

function gpu_add_admin_menu() {
    add_menu_page(
        'Gas Price Updates',
        'Gas Prices',
        'manage_options',
        'gas-price-updates',
        'gpu_admin_page',
        'dashicons-chart-line',
        30
    );
}

// Enqueue scripts and styles
add_action('admin_enqueue_scripts', 'gpu_enqueue_admin_scripts');
add_action('wp_enqueue_scripts', 'gpu_enqueue_frontend_scripts');

function gpu_enqueue_admin_scripts($hook) {
    if ('toplevel_page_gas-price-updates' !== $hook) {
        return;
    }
    wp_enqueue_style('gpu-admin-style', plugins_url('assets/css/admin.css', __FILE__));
    wp_enqueue_script('gpu-admin-script', plugins_url('assets/js/admin.js', __FILE__), array('jquery'), '1.0.0', true);
}

function gpu_enqueue_frontend_scripts() {
    wp_enqueue_style('gpu-frontend-style', plugins_url('assets/css/frontend.css', __FILE__));
    wp_enqueue_script('gpu-frontend-script', plugins_url('assets/js/frontend.js', __FILE__), array('jquery'), '1.0.0', true);
}

// Include required files
require_once plugin_dir_path(__FILE__) . 'includes/admin-page.php';
require_once plugin_dir_path(__FILE__) . 'includes/csv-handler.php';
require_once plugin_dir_path(__FILE__) . 'includes/shortcode.php';
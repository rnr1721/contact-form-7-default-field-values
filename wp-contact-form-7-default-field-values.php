<?php

/*
 * Plugin Name: Contact Form 7 default field values
 * Plugin URI: https://github.com/rnr1721/contact-form-7-default-field-values
 * Description: Plugin that can add default field values to some fields in contact-form-7 shortcodes
 * Author: Eugeny G
 * Author URI: https://github.com/rnr1721
 * License: MIT License
 * License URI: https://www.mit.edu/~amini/LICENSE.md
 * Version: 1.0.0
 * Requires at least: 6.2
 * Requires PHP: 7.4
 * RequiresPlugin: contact-form-7
 * Text Domain: cf7dfv
 */

// Add plugin scripts and css
function cf7_custom_field_values_admin_assets()
{
    if (is_admin()) {
        wp_enqueue_style('cf7-custom-field-values', plugins_url('assets/css/cf7dfv-style.css', __FILE__));
        wp_enqueue_script('cf7-custom-field-values', plugins_url('assets/js/cf7dfv-settings.js', __FILE__), array('jquery'), '1.0', true);
        load_plugin_textdomain('cf7dfv', false, dirname(plugin_basename(__FILE__)) . '/languages');
        wp_localize_script('cf7-custom-field-values', 'cf7dfv_ajax', array(
            'url' => admin_url('admin-ajax.php'),
            'already_exists' => __('Already exists', 'cf7dfv'),
            'edit_item_name' => __('Edit item name', 'cf7dfv')
        ));
    }
}

add_action('admin_enqueue_scripts', 'cf7_custom_field_values_admin_assets');

// Add admin page as child menu item of Contact-Form-7
function cf7_custom_field_values_menu()
{
    add_submenu_page(
            'wpcf7',
            'Contact form 7 default field values',
            'Fields for default values',
            'manage_options',
            'contact-form-7-default-field-values',
            'cf7_custom_field_values_settings'
    );
}

add_action('admin_menu', 'cf7_custom_field_values_menu');

// AJAX обработчики
add_action('wp_ajax_save_data', 'cf7_custom_field_values_save_data_callback');
add_action('wp_ajax_get_data', 'cf7_custom_field_values_get_data_callback');

// Callback for store data
function cf7_custom_field_values_save_data_callback()
{
    $items = filter_input(INPUT_POST, 'items', FILTER_SANITIZE_STRING);

    if (empty($items)) {
        $result = [];
    } else {
        $result = json_decode(htmlspecialchars_decode($items), true);
    }

    update_option('cf7dfv_fields', json_encode($result));
    wp_send_json_success($result);
}

// Callback for get data
function cf7_custom_field_values_get_data_callback()
{
    $items = get_option('cf7dfv_fields', '');

    if (empty($items)) {
        $result = [];
    } else {
        $result = json_decode($items, true);
    }

    wp_send_json_success($result);
}

// Display plugin page
function cf7_custom_field_values_settings()
{
    include(plugin_dir_path(__FILE__) . 'views/settings.php');
}

/**
 * Add shortcode parameter your-product for put product name in form on product page
 */
function cf7_custom_field_values_run($out, $pairs, $atts)
{
    $my_attrs_source = get_option('cf7dfv_fields', null);

    if ($my_attrs_source === null) {
        $my_attrs = [];
    } else {
        $my_attrs = json_decode($my_attrs_source, true);
    }

    foreach ($my_attrs as $my_attr) {
        if (isset($atts[$my_attr])) {
            $out[$my_attr] = $atts[$my_attr];
        }
    }
    return $out;
}

add_filter('shortcode_atts_wpcf7', 'cf7_custom_field_values_run', 10, 3);

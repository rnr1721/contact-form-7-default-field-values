<?php

/**
 * Register scripts, styles and localisation params
 */
function cf7_custom_field_values_admin_assets()
{

    $screen = get_current_screen();

    if ($screen && $screen->id === 'contact-form-7_page_contact-form-7-default-field-values') {
        wp_enqueue_style('cf7-custom-field-values', plugins_url('assets/css/cf7dfv-style.css', __FILE__));
        wp_enqueue_script('cf7-custom-field-values', plugins_url('assets/js/cf7dfv-settings.js', __FILE__), array('jquery'), '1.0', true);
        load_plugin_textdomain('cf7dfv', false, dirname(plugin_basename(__FILE__)) . '/languages');
        wp_localize_script('cf7-custom-field-values', 'cf7dfv_ajax', array(
            'url' => admin_url('admin-ajax.php'),
            'already_exists' => _e("Already exists", "cf7dfv"),
            'edit_item_name' => __('Edit item name', 'cf7dfv'),
            'edit' => __('Edit', 'cf7dfv'),
            'delete' => __('Delete', 'cf7dfv')
        ));
    }
}

add_action('admin_enqueue_scripts', 'cf7_custom_field_values_admin_assets');

/**
 * Add admin page as child menu item of Contact-Form-7
 */
function cf7_custom_field_values_menu()
{
    add_submenu_page(
            'wpcf7',
            __('Contact form 7 default field values', 'cf7dfv'),
            __('Fields for default values', 'cf7dfv'),
            'manage_options',
            'contact-form-7-default-field-values',
            'cf7_custom_field_values_settings'
    );
}

add_action('admin_menu', 'cf7_custom_field_values_menu');

/**
 * Callback for store data
 */
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

add_action('wp_ajax_save_data', 'cf7_custom_field_values_save_data_callback');
add_action('wp_ajax_get_data', 'cf7_custom_field_values_get_data_callback');

/**
 * Callback for get data
 */
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

/**
 * Display plugin page
 */
function cf7_custom_field_values_settings()
{
    include(plugin_dir_path(__FILE__) . 'views/settings.php');
}

// Add link to plugin settings in plugins list
add_action('admin_menu', 'cf7_custom_field_values_add_settings_link');

function cf7_custom_field_values_add_settings_link()
{
    add_filter('plugin_action_links_' . plugin_basename(__FILE__), function ($links) {
        $settings_link = '<a href="' . admin_url('admin.php?page=contact-form-7-default-field-values') . '">' . __('Settings', 'cf7dfv') . '</a>';
        array_unshift($links, $settings_link);
        return $links;
    });
}

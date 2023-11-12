<?php

/*
 * Plugin Name: Default field values for Contact Form 7
 * Plugin URI: https://github.com/rnr1721/contact-form-7-default-field-values
 * Description: Plugin that can add default field values to some fields in contact-form-7 shortcodes
 * Author: Eugeny G
 * Author URI: https://github.com/rnr1721
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Version: 1.0.0
 * Requires at least: 6.2
 * Requires PHP: 7.4
 * RequiresPlugin: contact-form-7
 * Text Domain: cf7dfv
 */

if (is_admin()) {
    include(dirname(__FILE__) . '/admin.php');
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

/**
 * This function add default values for fields in Contact Form 7 shortcode.
 * If params empty string or empty array it return source state of shortcode
 * 
 * @param string $shortcode
 * @param array|string $params Key=>Value array or string in format "param:value"
 * @return string Returns shortcode with added parameters
 */
function cf7_add_shortcode_params($shortcode, $params = array())
{
    if (empty($params)) {
        return $shortcode;
    }
    $shortcodeWithoutEndBrace = rtrim($shortcode, ' ]');
    if (is_string($params)) {
        $paramsExploded = explode(':', $params);
        $params = array(
            $paramsExploded[0] => $paramsExploded[1]
        );
    }
    foreach ($params as $param => $paramValue) {
        $shortcodeWithoutEndBrace .= ' ' . $param . '="' . $paramValue . '"';
    }
    return $shortcodeWithoutEndBrace . ']';
}

/**
 * Checks for the presence of a parameter in a shortcode within the content of
 * the current post.
 *
 * @param string $shortcode The name of the shortcode to check.
 * @param string $parameter The name of the parameter to check.
 * @return bool Returns true if the parameter exists, and false otherwise.
 */
function cf7_shortcode_has_param($shortcode, $parameter)
{
    $matches = [];
    preg_match_all('/\[(' . $shortcode . ')([^\]]*?)\]/', get_the_content(), $matches);

    if (empty($matches[0])) {
        return false;
    }

    foreach ($matches[2] as $shortcode_attributes) {
        $attributes = shortcode_parse_atts($shortcode_attributes);
        if (isset($attributes[$parameter])) {
            return true;
        }
    }

    return false;
}

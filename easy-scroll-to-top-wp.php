<?php
/**
 * Plugin Name: Simple Smooth Scroll Up
 * Description: Lightweight and customizable Scroll to Top plugin with a dedicated dashboard and SVG icons.
 * Version: 1.0.0
 * Author: Md Asif Mahmud
 * Text Domain: sssu
 * License: GPLv3
 */

if ( ! defined( 'ABSPATH' ) ) exit;

// 1. Create Settings Page (Dashboard)
add_action('admin_menu', 'sssu_add_admin_menu');
function sssu_add_admin_menu() {
    add_options_page('Scroll Up Settings', 'Scroll Up', 'manage_options', 'simple-smooth-scroll-up', 'sssu_settings_page');
}

// 2. Save default settings and data
add_action('admin_init', 'sssu_settings_init');
function sssu_settings_init() {
    register_setting('sssu_plugin_settings', 'sssu_options');

    add_settings_section('sssu_main_section', __('General Settings', 'sssu'), null, 'simple-smooth-scroll-up');

    // Fields for color and size settings will be registered here
}

// 3. Settings page HTML (Dashboard UI)
function sssu_settings_page() {
    ?>
    <div class="wrap">
        <h1><?php _e('Simple Smooth Scroll Up Settings', 'sssu'); ?></h1>
        <form action="options.php" method="post">
            <?php
            settings_fields('sssu_plugin_settings');
            $options = get_option('sssu_options');
            
            // Setting the default value
            $bg_color     = $options['bg_color'] ?? '#000000';
            $icon_color   = $options['icon_color'] ?? '#ffffff';
            $btn_size     = $options['btn_size'] ?? '45';
            $icon_size    = $options['icon_size'] ?? '24';
            $radius       = $options['radius'] ?? '5';
            $icon_type    = $options['icon_type'] ?? '1';
            ?>
            <table class="form-table">
                <tr>
                    <th>Background Color</th>
                    <td><input type="color" name="sssu_options[bg_color]" value="<?php echo esc_attr($bg_color); ?>"></td>
                </tr>
                <tr>
                    <th>Icon Color</th>
                    <td><input type="color" name="sssu_options[icon_color]" value="<?php echo esc_attr($icon_color); ?>"></td>
                </tr>
                <tr>
                    <th>Button Size (px)</th>
                    <td><input type="number" name="sssu_options[btn_size]" value="<?php echo esc_attr($btn_size); ?>"></td>
                </tr>
                <tr>
                    <th>Icon Size (px)</th>
                    <td><input type="number" name="sssu_options[icon_size]" value="<?php echo esc_attr($icon_size); ?>"></td>
                </tr>
                <tr>
                    <th>Border Radius (px)</th>
                    <td><input type="number" name="sssu_options[radius]" value="<?php echo esc_attr($radius); ?>"></td>
                </tr>
                <tr>
                    <th>Select Icon Shape</th>
                    <td>
                        <select name="sssu_options[icon_type]">
                            <option value="1" <?php selected($icon_type, '1'); ?>>Arrow Up</option>
                            <option value="2" <?php selected($icon_type, '2'); ?>>Chevron Up</option>
                            <option value="3" <?php selected($icon_type, '3'); ?>>Angle Up</option>
                        </select>
                    </td>
                </tr>
            </table>
            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}

// 4. SVG Icon Library
function sssu_get_svg($type) {
    $svgs = [
        '1' => '<svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M12 19V5M12 5L5 12M12 5L19 12" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>',
        '2' => '<svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M18 15L12 9L6 15" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>',
        '3' => '<svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M17 11L12 6L7 11M17 18L12 13L7 18" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>',
    ];
    return $svgs[$type] ?? $svgs['1'];
}

// ৫. স্ক্রিপ্ট এবং ডাইনামিক স্টাইল লোড
add_action('wp_enqueue_scripts', 'sssu_enqueue_assets');
function sssu_enqueue_assets() {
    $options = get_option('sssu_options');
    
    wp_enqueue_style('sssu-style', plugins_url('css/style.css', __FILE__));
    wp_enqueue_script('sssu-js', plugins_url('js/scrollup.js', __FILE__), array('jquery'), '2.4.1', true);

    // Dynamic JS Initialization
    $custom_js = "jQuery(document).ready(function($){ $.scrollUp({ scrollText: '" . sssu_get_svg($options['icon_type'] ?? '1') . "' }); });";
    wp_add_inline_script('sssu-js', $custom_js);

    // Dynamic CSS
    $bg      = $options['bg_color'] ?? '#000000';
    $color   = $options['icon_color'] ?? '#ffffff';
    $size    = ($options['btn_size'] ?? '45') . 'px';
    $i_size  = ($options['icon_size'] ?? '24') . 'px';
    $radius  = ($options['radius'] ?? '5') . 'px';

    $custom_css = "
        #scrollUp {
            background-color: $bg;
            color: $color;
            width: $size;
            height: $size;
            border-radius: $radius;
            display: flex;
            align-items: center;
            justify-content: center;
            bottom: 20px;
            right: 20px;
            text-decoration: none;
            transition: 0.3s;
        }
        #scrollUp svg {
            width: $i_size;
            height: $i_size;
        }
        #scrollUp:hover { opacity: 0.8; }";
    wp_add_inline_style('sssu-style', $custom_css);
}
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

// 2. Save settings
add_action('admin_init', 'sssu_settings_init');
function sssu_settings_init() {
    register_setting('sssu_plugin_settings', 'sssu_options');
}

// 3. Settings page HTML
function sssu_settings_page() {
    ?>
    <div class="wrap">
        <h1><?php _e('Simple Smooth Scroll Up Settings', 'sssu'); ?></h1>
        <form action="options.php" method="post" style="background: #fff; padding: 20px; border: 1px solid #ccd0d4; border-radius: 5px;">
            <?php
            settings_fields('sssu_plugin_settings');
            $options = get_option('sssu_options');
            
            // ডিফল্ট ভ্যালুগুলো এক জায়গায় সেট করা
            $bg_color     = $options['bg_color'] ?? '#000000';
            $icon_color   = $options['icon_color'] ?? '#ffffff';
            $btn_size     = $options['btn_size'] ?? '45';
            $icon_size    = $options['icon_size'] ?? '24';
            $radius       = $options['radius'] ?? '5';
            $icon_type    = $options['icon_type'] ?? '1';
            $position     = $options['position'] ?? 'right'; 
            ?>
            <table class="form-table">
                <tr>
                    <th>Background Color</th>
                    <td><input type="color" name="sssu_options[bg_color]" value="<?php echo esc_attr($bg_color); ?>"></td>
                </tr>
                <tr>
                    <th>Button Position</th>
                    <td>
                        <select name="sssu_options[position]">
                            <option value="right" <?php selected($position, 'right'); ?>>Right Side</option>
                            <option value="left" <?php selected($position, 'left'); ?>>Left Side</option>
                            <option value="center" <?php selected($position, 'center'); ?>>Center Bottom</option>
                        </select>
                    </td>
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

// 5. Load scripts and dynamic styles
add_action('wp_enqueue_scripts', 'sssu_enqueue_assets');
function sssu_enqueue_assets() {
    $options = get_option('sssu_options');
    
    wp_enqueue_style('sssu-style', plugins_url('css/style.css', __FILE__));
    wp_enqueue_script('sssu-js', plugins_url('js/scrollup.js', __FILE__), array('jquery'), '2.4.1', true);

    // JS Initialization
    $icon_svg = sssu_get_svg($options['icon_type'] ?? '1');
    $custom_js = "jQuery(document).ready(function($){ $.scrollUp({ scrollText: '$icon_svg' }); });";
    wp_add_inline_script('sssu-js', $custom_js);

    // Dynamic CSS variables
    $bg       = $options['bg_color'] ?? '#000000';
    $color    = $options['icon_color'] ?? '#ffffff';
    $size     = ($options['btn_size'] ?? '45') . 'px';
    $i_size   = ($options['icon_size'] ?? '24') . 'px';
    $radius   = ($options['radius'] ?? '5') . 'px';
    $pos_type = $options['position'] ?? 'right';

    // Position CSS logic
    if ($pos_type === 'left') {
        $pos_css = "left: 20px; right: auto;";
    } elseif ($pos_type === 'center') {
        $pos_css = "left: 50%; transform: translateX(-50%); right: auto;";
    } else {
        $pos_css = "right: 20px; left: auto;";
    }

    $custom_css = "
        #scrollUp {
            bottom: 20px;
            $pos_css
            background-color: " . esc_attr($bg) . ";
            color: " . esc_attr($color) . ";
            width: " . esc_attr($size) . ";
            height: " . esc_attr($size) . ";
            border-radius: " . esc_attr($radius) . ";
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            transition: 0.3s;
        }
        #scrollUp svg {
            width: " . esc_attr($i_size) . ";
            height: " . esc_attr($i_size) . ";
        }
        #scrollUp:hover { opacity: 0.8; }";
    wp_add_inline_style('sssu-style', $custom_css);
}
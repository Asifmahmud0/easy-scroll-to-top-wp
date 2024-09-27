<?php
/**
 * Plugin Name: Easy Scroll To Top Wp
 * Description: "Easy Scroll To Top WP" is a lightweight WordPress plugin that adds a smooth and customizable 'Scroll to Top' button to your website, enhancing user experience by allowing visitors to easily navigate back to the top of the page with a single click. Ideal for any site layout, it offers simple installation and flexibility.
 * Plugin URI:https://wordpress.org/plugins/easy-scroll-to-top-wp/
 * Author: Md Asif Mahmud
 * Version: 1.0.0
 * Author URI: 
 * Requires at least: 6.3
 *Requires PHP: 7.4
 * Text Domain: esttw
 * Update URI:
 * icense: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl-3.0.html
 */


 // including css
 function esttw_style_css(){
    wp_enqueue_style('esttw-style', plugins_url( 'css/esttw-style.css', __FILE__ ));
 }
 add_action("wp_enqueue_scripts","esttw_style_css");

 // including js
 function esttw_style_js(){
    wp_enqueue_script('jquery');

    wp_enqueue_script('esttw-plugin', plugins_url( 'js/esttw-plugin.js', __FILE__ ),array(),
    '1.0.0', 'ture');
 }
 add_action("wp_enqueue_scripts","esttw_style_js");

//  jquery plugin activate
function esttw_jquery_script(){
    ?>
            <script>
                jQuery(document).ready(function () {
                 jQuery.scrollUp();
                });
            </script> 
    <?php
}
add_action("wp_footer", "esttw_jquery_script");

// Plugin Customize Setting
add_action('customize_register','esttw_scroll_to_top');

function esttw_scroll_to_top($wp_customize){
    $wp_customize -> add_section('esttw_scroll_to_top_section', array(
        'title' => __('Scroll To Top','esttw'),
        'description' => 'Easy Scroll To Top WP is a lightweight WordPress plugin that adds a smooth and customizable Scroll to Top button to your website, enhancing user experience by allowing visitors to easily navigate back to the top of the page with a single click. Ideal for any site layout, it offers simple installation and flexibility.',
    ));
    $wp_customize -> add_setting('esttw_background_color', array(
        'default' => '#00000',
    ));
    $wp_customize -> add_control('esttw_background_color', array(
        'label' => 'Background Color',
        'section' => 'esttw_scroll_to_top_section',
        'type' => 'color',
    ));
    //Border Radius 
    $wp_customize -> add_setting('esttw_border_radius', array(
        'default' => '5px',
    ));
    $wp_customize -> add_control('esttw_border_radius', array(
        'label' => 'Border Radius',
        'section' => 'esttw_scroll_to_top_section',
        'type' => 'text',
    ));

}
// Theme fucntion Customize register css actived
 function esttw_theme_customizer_color(){
    ?>
    <style>
       #scrollUp {
        background-color: <?php print get_theme_mod('esttw_background_color')?>;
        border-radius: <?php print get_theme_mod('esttw_border_radius')?>;
       }
    </style>
    <?php


 }
 add_action('wp_head', 'esttw_theme_customizer_color');

 ?>

<?php
/**
 * Plugin Name:       Simple Smooth Scroll Up
 * Plugin URI:        https://wordpress.org/plugins/simple-smooth-scroll-up/
 * Description:       Lightweight and customizable Scroll to Top plugin with a dedicated settings page and SVG icons.
 * Version:           1.0.0
 * Requires at least: 5.0
 * Requires PHP:      7.4
 * Tested up to:      6.9
 * Author:            Md Asif Mahmud
 * Author URI:        https://profiles.wordpress.org/your-username/
 * Text Domain:       simple-smooth-scroll-up
 * License:           GPLv3
 * License URI:       https://www.gnu.org/licenses/gpl-3.0.html
 */

if ( ! defined( 'ABSPATH' ) ) exit;

// ─── 1. Load text domain ────────────────────────────────────────────────────
add_action( 'plugins_loaded', 'sssu_load_textdomain' );
function sssu_load_textdomain() {
    load_plugin_textdomain( 'simple-smooth-scroll-up', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
}

// ─── 2. Admin menu ──────────────────────────────────────────────────────────
add_action( 'admin_menu', 'sssu_add_admin_menu' );
function sssu_add_admin_menu() {
    add_options_page(
        __( 'Scroll Up Settings', 'simple-smooth-scroll-up' ),
        __( 'Scroll Up', 'simple-smooth-scroll-up' ),
        'manage_options',
        'simple-smooth-scroll-up',
        'sssu_settings_page'
    );
}

// ─── 3. Register setting with sanitization ──────────────────────────────────
add_action( 'admin_init', 'sssu_settings_init' );
function sssu_settings_init() {
    register_setting(
        'sssu_plugin_settings',
        'sssu_options',
        array(
            'sanitize_callback' => 'sssu_sanitize_options',
        )
    );
}

/**
 * Sanitize all plugin options before saving.
 */
function sssu_sanitize_options( $input ) {
    $clean = array();

    // Hex color fields
    foreach ( array( 'bg_color', 'icon_color', 'hover_color' ) as $key ) {
        if ( isset( $input[ $key ] ) ) {
            $clean[ $key ] = sanitize_hex_color( $input[ $key ] );
        }
    }

    // Integer/pixel fields
    foreach ( array( 'btn_size', 'icon_size', 'radius', 'scroll_distance', 'scroll_speed' ) as $key ) {
        if ( isset( $input[ $key ] ) ) {
            $clean[ $key ] = absint( $input[ $key ] );
        }
    }

    // Whitelisted string fields
    $allowed_positions   = array( 'right', 'left', 'center' );
    $allowed_icon_types  = array( '1', '2', '3' );
    $allowed_animations  = array( 'fade', 'slide', 'none' );

    $clean['position']  = in_array( $input['position'] ?? 'right', $allowed_positions, true )
                            ? $input['position']
                            : 'right';

    $clean['icon_type'] = in_array( $input['icon_type'] ?? '1', $allowed_icon_types, true )
                            ? $input['icon_type']
                            : '1';

    $clean['animation'] = in_array( $input['animation'] ?? 'fade', $allowed_animations, true )
                            ? $input['animation']
                            : 'fade';

    // Checkboxes
    $clean['hide_on_mobile'] = ! empty( $input['hide_on_mobile'] ) ? '1' : '0';

    return $clean;
}

// ─── 4. Settings page HTML ──────────────────────────────────────────────────
function sssu_settings_page() {
    if ( ! current_user_can( 'manage_options' ) ) {
        return;
    }
    $options = get_option( 'sssu_options', array() );

    // Defaults
    $bg_color        = $options['bg_color']        ?? '#000000';
    $icon_color      = $options['icon_color']      ?? '#ffffff';
    $hover_color     = $options['hover_color']     ?? '#444444';
    $btn_size        = $options['btn_size']        ?? 45;
    $icon_size       = $options['icon_size']       ?? 24;
    $radius          = $options['radius']          ?? 5;
    $scroll_distance = $options['scroll_distance'] ?? 300;
    $scroll_speed    = $options['scroll_speed']    ?? 300;
    $icon_type       = $options['icon_type']       ?? '1';
    $position        = $options['position']        ?? 'right';
    $animation       = $options['animation']       ?? 'fade';
    $hide_on_mobile  = $options['hide_on_mobile']  ?? '0';
    ?>
    <div class="wrap">
        <h1><?php esc_html_e( 'Simple Smooth Scroll Up Settings', 'simple-smooth-scroll-up' ); ?></h1>
        <form action="options.php" method="post" style="background:#fff;padding:20px;border:1px solid #ccd0d4;border-radius:5px;max-width:700px;">
            <?php settings_fields( 'sssu_plugin_settings' ); ?>
            <table class="form-table" role="presentation">

                <tr>
                    <th scope="row"><?php esc_html_e( 'Button Position', 'simple-smooth-scroll-up' ); ?></th>
                    <td>
                        <select name="sssu_options[position]">
                            <option value="right"  <?php selected( $position, 'right' ); ?>><?php esc_html_e( 'Right side', 'simple-smooth-scroll-up' ); ?></option>
                            <option value="left"   <?php selected( $position, 'left' ); ?>><?php esc_html_e( 'Left side', 'simple-smooth-scroll-up' ); ?></option>
                            <option value="center" <?php selected( $position, 'center' ); ?>><?php esc_html_e( 'Center bottom', 'simple-smooth-scroll-up' ); ?></option>
                        </select>
                    </td>
                </tr>

                <tr>
                    <th scope="row"><?php esc_html_e( 'Background Color', 'simple-smooth-scroll-up' ); ?></th>
                    <td><input type="color" name="sssu_options[bg_color]" value="<?php echo esc_attr( $bg_color ); ?>"></td>
                </tr>

                <tr>
                    <th scope="row"><?php esc_html_e( 'Hover Background Color', 'simple-smooth-scroll-up' ); ?></th>
                    <td><input type="color" name="sssu_options[hover_color]" value="<?php echo esc_attr( $hover_color ); ?>"></td>
                </tr>

                <tr>
                    <th scope="row"><?php esc_html_e( 'Icon Color', 'simple-smooth-scroll-up' ); ?></th>
                    <td><input type="color" name="sssu_options[icon_color]" value="<?php echo esc_attr( $icon_color ); ?>"></td>
                </tr>

                <tr>
                    <th scope="row"><?php esc_html_e( 'Button Size (px)', 'simple-smooth-scroll-up' ); ?></th>
                    <td><input type="number" name="sssu_options[btn_size]" value="<?php echo esc_attr( $btn_size ); ?>" min="20" max="120"></td>
                </tr>

                <tr>
                    <th scope="row"><?php esc_html_e( 'Icon Size (px)', 'simple-smooth-scroll-up' ); ?></th>
                    <td><input type="number" name="sssu_options[icon_size]" value="<?php echo esc_attr( $icon_size ); ?>" min="10" max="80"></td>
                </tr>

                <tr>
                    <th scope="row"><?php esc_html_e( 'Border Radius (px)', 'simple-smooth-scroll-up' ); ?></th>
                    <td><input type="number" name="sssu_options[radius]" value="<?php echo esc_attr( $radius ); ?>" min="0" max="999"></td>
                </tr>

                <tr>
                    <th scope="row"><?php esc_html_e( 'Show After Scrolling (px)', 'simple-smooth-scroll-up' ); ?></th>
                    <td>
                        <input type="number" name="sssu_options[scroll_distance]" value="<?php echo esc_attr( $scroll_distance ); ?>" min="0" max="9999">
                        <p class="description"><?php esc_html_e( 'Button appears after scrolling this many pixels down the page.', 'simple-smooth-scroll-up' ); ?></p>
                    </td>
                </tr>

                <tr>
                    <th scope="row"><?php esc_html_e( 'Scroll Speed (ms)', 'simple-smooth-scroll-up' ); ?></th>
                    <td>
                        <input type="number" name="sssu_options[scroll_speed]" value="<?php echo esc_attr( $scroll_speed ); ?>" min="0" max="5000">
                        <p class="description"><?php esc_html_e( 'How fast (in milliseconds) the page scrolls to the top.', 'simple-smooth-scroll-up' ); ?></p>
                    </td>
                </tr>

                <tr>
                    <th scope="row"><?php esc_html_e( 'Appearance Animation', 'simple-smooth-scroll-up' ); ?></th>
                    <td>
                        <select name="sssu_options[animation]">
                            <option value="fade"  <?php selected( $animation, 'fade' ); ?>><?php esc_html_e( 'Fade', 'simple-smooth-scroll-up' ); ?></option>
                            <option value="slide" <?php selected( $animation, 'slide' ); ?>><?php esc_html_e( 'Slide', 'simple-smooth-scroll-up' ); ?></option>
                            <option value="none"  <?php selected( $animation, 'none' ); ?>><?php esc_html_e( 'None', 'simple-smooth-scroll-up' ); ?></option>
                        </select>
                    </td>
                </tr>

                <tr>
                    <th scope="row"><?php esc_html_e( 'Select Icon Shape', 'simple-smooth-scroll-up' ); ?></th>
                    <td>
                        <select name="sssu_options[icon_type]">
                            <option value="1" <?php selected( $icon_type, '1' ); ?>><?php esc_html_e( 'Arrow up', 'simple-smooth-scroll-up' ); ?></option>
                            <option value="2" <?php selected( $icon_type, '2' ); ?>><?php esc_html_e( 'Chevron up', 'simple-smooth-scroll-up' ); ?></option>
                            <option value="3" <?php selected( $icon_type, '3' ); ?>><?php esc_html_e( 'Double chevron up', 'simple-smooth-scroll-up' ); ?></option>
                        </select>
                    </td>
                </tr>

                <tr>
                    <th scope="row"><?php esc_html_e( 'Hide on Mobile', 'simple-smooth-scroll-up' ); ?></th>
                    <td>
                        <label>
                            <input type="checkbox" name="sssu_options[hide_on_mobile]" value="1" <?php checked( $hide_on_mobile, '1' ); ?>>
                            <?php esc_html_e( 'Hide the button on screens narrower than 768px.', 'simple-smooth-scroll-up' ); ?>
                        </label>
                    </td>
                </tr>

            </table>
            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}

// ─── 5. SVG icon library ────────────────────────────────────────────────────
function sssu_get_svg( $type ) {
    $svgs = array(
        '1' => '<svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><path d="M12 19V5M12 5L5 12M12 5L19 12" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>',
        '2' => '<svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><path d="M18 15L12 9L6 15" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>',
        '3' => '<svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><path d="M17 11L12 6L7 11M17 18L12 13L7 18" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>',
    );
    return $svgs[ $type ] ?? $svgs['1'];
}

// ─── 6. Enqueue assets and dynamic styles ───────────────────────────────────
add_action( 'wp_enqueue_scripts', 'sssu_enqueue_assets' );
function sssu_enqueue_assets() {
    $options = get_option( 'sssu_options', array() );

    wp_enqueue_style(
        'sssu-style',
        plugins_url( 'css/style.css', __FILE__ ),
        array(),
        '1.0.0'
    );

    wp_enqueue_script(
        'sssu-js',
        plugins_url( 'js/scrollup.js', __FILE__ ),
        array( 'jquery' ),
        '2.4.1',
        true
    );

    // Build JS init — pass options safely via wp_localize_script instead of
    // embedding them directly in inline JS to avoid any escaping issues.
    $scroll_distance = absint( $options['scroll_distance'] ?? 300 );
    $scroll_speed    = absint( $options['scroll_speed']    ?? 300 );
    $animation_raw   = $options['animation'] ?? 'fade';
    $animation       = in_array( $animation_raw, array( 'fade', 'slide', 'none' ), true )
                         ? $animation_raw
                         : 'fade';
    // Map 'none' to the scrollUp library's equivalent
    if ( $animation === 'none' ) {
        $animation = 'false';
    }

    wp_localize_script( 'sssu-js', 'sssyConfig', array(
        'scrollDistance' => $scroll_distance,
        'scrollSpeed'    => $scroll_speed,
        'animation'      => $animation,
        'iconSvg'        => sssu_get_svg( $options['icon_type'] ?? '1' ),
    ) );

    // Inline init script
    $init_js = "
(function($){
    $(document).ready(function(){
        var cfg = window.sssyConfig || {};
        $.scrollUp({
            scrollDistance: parseInt(cfg.scrollDistance, 10) || 300,
            scrollSpeed:    parseInt(cfg.scrollSpeed, 10)    || 300,
            animation:      cfg.animation !== 'false' ? cfg.animation : false,
            scrollText:     cfg.iconSvg   || ''
        });
    });
}(jQuery));";
    wp_add_inline_script( 'sssu-js', $init_js );

    // ── Dynamic CSS ──
    $bg          = sanitize_hex_color( $options['bg_color']    ?? '#000000' );
    $hover_bg    = sanitize_hex_color( $options['hover_color'] ?? '#444444' );
    $color       = sanitize_hex_color( $options['icon_color']  ?? '#ffffff' );
    $size        = absint( $options['btn_size']  ?? 45 ) . 'px';
    $i_size      = absint( $options['icon_size'] ?? 24 ) . 'px';
    $radius_val  = absint( $options['radius']    ?? 5  ) . 'px';
    $pos_type    = $options['position'] ?? 'right';

    if ( $pos_type === 'left' ) {
        $pos_css = 'left:20px;right:auto;';
    } elseif ( $pos_type === 'center' ) {
        $pos_css = 'left:50%;transform:translateX(-50%);right:auto;';
    } else {
        $pos_css = 'right:20px;left:auto;';
    }

    $hide_mobile_css = ( ! empty( $options['hide_on_mobile'] ) && $options['hide_on_mobile'] === '1' )
        ? '@media(max-width:767px){#scrollUp{display:none!important;}}'
        : '';

    $custom_css = "
#scrollUp {
    bottom: 20px;
    {$pos_css}
    background-color: {$bg};
    color: {$color};
    width: {$size};
    height: {$size};
    border-radius: {$radius_val};
    display: flex;
    align-items: center;
    justify-content: center;
    text-decoration: none;
    transition: background-color 0.3s, opacity 0.3s;
    box-sizing: border-box;
}
#scrollUp svg {
    width: {$i_size};
    height: {$i_size};
}
#scrollUp:hover {
    background-color: {$hover_bg};
}
{$hide_mobile_css}";

    wp_add_inline_style( 'sssu-style', $custom_css );
}

// ─── 7. Accessibility: add aria-label to the button via wp_footer ────────────
add_action( 'wp_footer', 'sssu_add_aria_label' );
function sssu_add_aria_label() {
    ?>
    <script>
    (function(){
        var btn = document.getElementById('scrollUp');
        if(btn && !btn.getAttribute('aria-label')){
            btn.setAttribute('aria-label', '<?php echo esc_js( __( 'Scroll to top', 'simple-smooth-scroll-up' ) ); ?>');
            btn.setAttribute('role', 'button');
        }
    })();
    </script>
    <?php
}
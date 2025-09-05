<?php

function get_api_url(){

    if(wp_get_environment_type() != 'local' && wp_get_environment_type() != 'staging')
        return API_PATH;

    return !empty($_COOKIE['__PP_KEY_ON_API_PATH']) ? $_COOKIE['__PP_KEY_ON_API_PATH'] : API_PATH;
}

function pozyczka_scripts() {
    // Подключение базовых стилей
    wp_enqueue_style('pozyczka-style', get_template_directory_uri() . '/assets/styles/app.min.css', [], CSS_VERSION);

    // ===============================
    // 1. БАЗОВЫЕ СТИЛИ (без кук)
    // ===============================
    if (is_front_page() || get_page_template_slug() === 'templates/google_page.php') {
        $first_loan  = get_field('first_loan');
        $second_loan = get_field('second_loan');

        $background_style = '
            .top-section.main.first-loan .custom-section { 
                background-image: url("' . esc_url($first_loan['image']['url'] ?? '') . '");
            }
            .top-section.main.second-loan .custom-section { 
                background-image: url("' . esc_url($second_loan['image']['url'] ?? '') . '");
            }

            @media screen and (max-width: 768px) {
                .top-section.main.first-loan .custom-section {
                    background-image: url("' . esc_url($first_loan['image_mobile']['url'] ?? '') . '");
                    aspect-ratio: 840 / 470;
                    background-size: contain;
                    height: 100%;
                }
                .top-section.main.second-loan .custom-section {
                    background-image: url("' . esc_url($second_loan['image_mobile']['url'] ?? '') . '");
                    aspect-ratio: 840 / 470;
                    background-size: contain;
                    height: 100%;
                }
            }
        ';
        wp_add_inline_style('pozyczka-style', $background_style);
    }

    if (is_page_template('templates/seo_page.php') || is_single()) {
        $front_page_id = (int) get_option('page_on_front');
        $first_loan    = get_field('first_loan', $front_page_id);
        $second_loan   = get_field('second_loan', $front_page_id);

        $background_style = '
            .main.banner-post-blog.first-loan { 
                background-image: url("' . esc_url($first_loan['image_posts'] ?? '') . '");
                aspect-ratio: 1160 / 650;
                background-size: contain;
                height: auto;
            }
            .main.banner-post-blog.second-loan { 
                background-image: url("' . esc_url($second_loan['image_posts'] ?? '') . '");
                aspect-ratio: 1160 / 650;
                background-size: contain;
                height: auto;
            }
        ';
        wp_add_inline_style('pozyczka-style', $background_style);
    }

    // ===============================
    // 2. ДОП. СТИЛИ (с кукой)
    // ===============================
    if (isset($_COOKIE['calculatorTab']) && is_numeric($_COOKIE['calculatorTab']) &&
        (is_front_page() || get_page_template_slug() === 'templates/google_page.php')) {

        $value       = (int) $_COOKIE['calculatorTab'];
        $first_loan  = get_field('first_loan');
        $second_loan = get_field('second_loan');

        $background_style = '';

        if ($value === 1 && !empty($first_loan['image']['url'])) {
            $background_style .= '
                html[data-tab="1"] .top-section.main.first-loan .custom-section {
                    background-image: url("' . esc_url($first_loan['image']['url']) . '");
                }
                @media screen and (max-width: 768px) {
                    html[data-tab="1"] .top-section.main.first-loan .custom-section {
                        background-image: url("' . esc_url($first_loan['image_mobile']['url'] ?? '') . '");
                        aspect-ratio: 840 / 470;
                        background-size: contain;
                        height: 100%;
                    }
                }
            ';
        } elseif ($value === 2 && !empty($second_loan['image']['url'])) {
            $background_style .= '
                html[data-tab="2"] .top-section.main.second-loan .custom-section {
                    background-image: url("' . esc_url($second_loan['image']['url']) . '");
                }
                @media screen and (max-width: 768px) {
                    html[data-tab="2"] .top-section.main.second-loan .custom-section {
                        background-image: url("' . esc_url($second_loan['image_mobile']['url'] ?? '') . '");
                        aspect-ratio: 840 / 470;
                        background-size: contain;
                        height: 100%;
                    }
                }
            ';
        }

        if ($background_style) {
            wp_add_inline_style('pozyczka-style', $background_style);
        }
    }
}
add_action('wp_enqueue_scripts', 'pozyczka_scripts');


if ( function_exists('add_theme_support') ) {
    add_theme_support('post-thumbnails');
    add_theme_support('menus');
    add_theme_support('title-tag');
}

add_image_size('SinglePost', 1160, 650, array( 'center', 'center' ));
add_image_size('ListPost', 376, 211, array( 'center', 'center' ));

remove_image_size( '1536x1536' );
remove_image_size( '2048x2048' );

update_option('medium_large_size_w', '0');
update_option('medium_large_size_h', '0');

add_action('after_setup_theme', function () {
    register_nav_menus(array(
        'header_menu' => 'Header menu',
        'sidebar_menu' => 'Sidebar Menu'
    ));
});

// Disable cf7
function wpcf7_remove_assets() {
    add_filter('wpcf7_load_js', '__return_false');
    add_filter('wpcf7_load_css', '__return_false');

    add_filter('wpcf7cf_load_js', '__return_false');
    add_filter('wpcf7cf_load_css', '__return_false');
}
add_action('wpcf7_init', 'wpcf7_remove_assets');

// Support SVG enable
function cc_mime_types($mimes) {
    $mimes['svg'] = 'image/svg+xml';
    $mimes['svgz'] = 'image/svg+xml';

    return $mimes;
}

add_filter( 'upload_mimes', 'cc_mime_types' );

add_filter( 'xmlrpc_enabled', '__return_false' );
add_filter( 'wp_mail_smtp_reports_emails_summary_is_disabled', '__return_true' );

add_filter('xmlrpc_methods', 'rxpp_remove_xmlrpc_pingback_ping');
function rxpp_remove_xmlrpc_pingback_ping($methods)
{
    unset($methods['pingback.ping']);
    $rxpp_blocked_methods_count = get_option('rxpp_blocked_methods_count', 0);
    $rxpp_blocked_methods_count++;
    update_option('rxpp_blocked_methods_count', $rxpp_blocked_methods_count, false);
    return $methods;
}


add_shortcode('custom_button', 'custom_button');

function custom_button( $atts = array(), $content = null ) {
    extract(shortcode_atts(array(
        'link' => '#'
    ), $atts));

    return '<div class="form__button custom"><a href="'.$link.'">'.$content.'</a></div>';
}

remove_filter( 'wp_robots', 'wp_robots_max_image_preview_large' );

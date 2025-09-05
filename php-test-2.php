<?php
function get_additional_html_attrs() {
    $attrs = '';

    if (isset($_COOKIE['__PP_KEY_ADVANCED_SETTING_CONTRAST']) && $_COOKIE['__PP_KEY_ADVANCED_SETTING_CONTRAST'] === 'true') {
        $attrs .= ' data-font-contrast="1"';
    }

    if (isset($_COOKIE['__PP_KEY_ADVANCED_SETTING_TEXT_SIZE'])) {
        $value = trim($_COOKIE['__PP_KEY_ADVANCED_SETTING_TEXT_SIZE']);

        if (is_numeric($value)) {
            $attrs .= ' data-font-scale="' . esc_attr($value) . '"';
        }
    }

    return $attrs;
}
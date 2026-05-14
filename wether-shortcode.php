<?php
/**
 * Plugin Name: Weather Shortcode
 * Description: Registers [weather] and [wether] shortcodes for simple weather output.
 * Version: 1.0.0
 */

if (! defined('ABSPATH')) {
    exit;
}

function weather_shortcode_render($atts = array(), $content = null, $shortcode_tag = 'weather')
{
    $atts = shortcode_atts(
        array(
            'city' => '',
            'condition' => '',
            'temperature' => '',
            'unit' => 'C',
        ),
        $atts,
        $shortcode_tag
    );

    $city = sanitize_text_field((string) $atts['city']);
    $condition = sanitize_text_field((string) $atts['condition']);
    $temperature = sanitize_text_field((string) $atts['temperature']);
    $unit = strtoupper(sanitize_text_field((string) $atts['unit']));
    $unit = in_array($unit, array('C', 'F'), true) ? $unit : 'C';
    $content = is_string($content) ? trim(wp_strip_all_tags($content)) : '';

    $parts = array_filter(
        array(
            $city,
            $condition,
            $temperature !== '' ? sprintf('%s°%s', $temperature, $unit) : '',
            $content,
        )
    );

    if (! $parts) {
        $parts[] = 'Weather details are unavailable.';
    }

    return sprintf(
        '<div class="weather-shortcode">%s</div>',
        esc_html(implode(' — ', $parts))
    );
}

add_shortcode('weather', 'weather_shortcode_render');
add_shortcode('wether', 'weather_shortcode_render');

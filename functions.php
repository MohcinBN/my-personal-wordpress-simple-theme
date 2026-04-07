<?php
/**
 * Mochin Theme functions and definitions
 */

if (!defined('ABSPATH')) {
    exit;
}

define('MOCHIN_THEME_VERSION', '1.0.0');

/**
 * Theme Setup
 */
function mochin_theme_setup() {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('automatic-feed-links');
    add_theme_support('html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
        'style',
        'script',
    ));
    add_theme_support('custom-logo', array(
        'height'      => 100,
        'width'       => 400,
        'flex-height' => true,
        'flex-width'  => true,
    ));

    register_nav_menus(array(
        'primary' => __('Primary Menu', 'mochin-theme'),
    ));
}
add_action('after_setup_theme', 'mochin_theme_setup');

/**
 * Enqueue scripts and styles
 */
function mochin_theme_scripts() {
    // Main CSS
    wp_enqueue_style('mochin-main', get_template_directory_uri() . '/assets/css/main.css', array(), MOCHIN_THEME_VERSION);
    
    // Main JS
    wp_enqueue_script('mochin-main', get_template_directory_uri() . '/assets/js/main.js', array(), MOCHIN_THEME_VERSION, true);
    
    if (is_singular() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }
}
add_action('wp_enqueue_scripts', 'mochin_theme_scripts');

/**
 * Custom excerpt length
 */
function mochin_excerpt_length($length) {
    return 30;
}
add_filter('excerpt_length', 'mochin_excerpt_length');

/**
 * Custom excerpt more
 */
function mochin_excerpt_more($more) {
    return '...';
}
add_filter('excerpt_more', 'mochin_excerpt_more');

/**
 * Add body classes
 */
function mochin_body_classes($classes) {
    if (is_singular()) {
        $classes[] = 'singular';
    }
    return $classes;
}
add_filter('body_class', 'mochin_body_classes');

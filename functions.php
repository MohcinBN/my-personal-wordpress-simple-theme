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

/**
 * Take post screenshot from browser and using it as a thumbnail image when you share posts on social media
 */

function mochin_save_screenshot_ajax() {
    check_ajax_referer('mochin_screenshot_nonce', 'nonce');
    
    if (!isset($_POST['image']) || !isset($_POST['post_id'])) {
        wp_send_json_error('Missing required data');
    }
    
    $post_id = intval($_POST['post_id']);
    $image_data = $_POST['image'];
    
    if (!current_user_can('edit_post', $post_id)) {
        wp_send_json_error('Permission denied');
    }
    
    $result = mochin_save_screenshot_image($post_id, $image_data);
    
    if ($result) {
        wp_send_json_success(array(
            'message' => 'Screenshot saved successfully',
            'attachment_id' => $result
        ));
    } else {
        wp_send_json_error('Failed to save screenshot');
    }
}
add_action('wp_ajax_mochin_save_screenshot', 'mochin_save_screenshot_ajax');

function mochin_save_screenshot_image($post_id, $image_data) {
    $image_data = str_replace('data:image/png;base64,', '', $image_data);
    $image_data = str_replace(' ', '+', $image_data);
    $decoded = base64_decode($image_data);
    
    if (!$decoded) {
        return false;
    }
    
    $upload_dir = wp_upload_dir();
    $post_slug = get_post_field('post_name', $post_id);
    $filename = 'screenshot-' . $post_slug . '-' . time() . '.png';
    $filepath = $upload_dir['path'] . '/' . $filename;
    
    if (!file_put_contents($filepath, $decoded)) {
        return false;
    }
    
    $filetype = wp_check_filetype($filename, null);
    $attachment = array(
        'post_mime_type' => $filetype['type'],
        'post_title' => sanitize_file_name($filename),
        'post_content' => '',
        'post_status' => 'inherit'
    );
    
    $attachment_id = wp_insert_attachment($attachment, $filepath, $post_id);
    
    if (!$attachment_id) {
        return false;
    }
    
    require_once(ABSPATH . 'wp-admin/includes/image.php');
    $attach_data = wp_generate_attachment_metadata($attachment_id, $filepath);
    wp_update_attachment_metadata($attachment_id, $attach_data);
    
    set_post_thumbnail($post_id, $attachment_id);
    
    return $attachment_id;
}

/**
 * Generate Open Graph image URL for social sharing
 */
function mochin_get_og_image_url($post_id) {
    $og_image = get_post_meta($post_id, '_mochin_og_image', true);
    
    if ($og_image && file_exists(get_attached_file($og_image))) {
        return wp_get_attachment_url($og_image);
    }
    
    if (has_post_thumbnail($post_id)) {
        return get_the_post_thumbnail_url($post_id, 'full');
    }
    
    return home_url('/wp-content/themes/mochin-theme/screenshot-generator.php?post_id=' . $post_id);
}

/**
 * Add Open Graph meta tags for social sharing
 */
function mochin_add_og_meta_tags() {
    if (!is_singular('post')) {
        return;
    }
    
    global $post;
    $post_id = $post->ID;
    
    $title = get_the_title();
    $description = get_the_excerpt() ? get_the_excerpt() : wp_trim_words(strip_tags($post->post_content), 30);
    $url = get_permalink();
    $image = mochin_get_og_image_url($post_id);
    $site_name = get_bloginfo('name');
    
    echo "\n<!-- Open Graph Meta Tags for Social Sharing -->\n";
    echo '<meta property="og:type" content="article" />' . "\n";
    echo '<meta property="og:title" content="' . esc_attr($title) . '" />' . "\n";
    echo '<meta property="og:description" content="' . esc_attr($description) . '" />' . "\n";
    echo '<meta property="og:url" content="' . esc_url($url) . '" />' . "\n";
    echo '<meta property="og:image" content="' . esc_url($image) . '" />' . "\n";
    echo '<meta property="og:image:width" content="1200" />' . "\n";
    echo '<meta property="og:image:height" content="630" />' . "\n";
    echo '<meta property="og:site_name" content="' . esc_attr($site_name) . '" />' . "\n";
    
    echo '<meta name="twitter:card" content="summary_large_image" />' . "\n";
    echo '<meta name="twitter:title" content="' . esc_attr($title) . '" />' . "\n";
    echo '<meta name="twitter:description" content="' . esc_attr($description) . '" />' . "\n";
    echo '<meta name="twitter:image" content="' . esc_url($image) . '" />' . "\n";
    echo "<!-- End Open Graph Meta Tags -->\n\n";
}
add_action('wp_head', 'mochin_add_og_meta_tags');

/**
 * Include Anti-Robot Check functionality
 */
require_once get_template_directory() . '/inc/anti-robot-check.php';

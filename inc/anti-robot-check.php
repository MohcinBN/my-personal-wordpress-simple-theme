<?php
/**
 * Anti-Robot Check for Comments
 * Simple spam protection without dependencies
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Start session for anti-robot check
 */
function mochin_start_session() {
    if (!session_id()) {
        session_start();
    }
}
add_action('init', 'mochin_start_session');

/**
 * Generate random challenge
 */
function mochin_generate_robot_check() {
    $numberMap = array(
        'one', 'two', 'three', 'four', 'five',
        'six', 'seven', 'eight', 'nine', 'ten'
    );
    $randomNumber = rand(1, 99);
    $randomWord = $numberMap[array_rand($numberMap)];
    $challenge = $randomWord . $randomNumber;
    
    $_SESSION['mochin_robot_check'] = $challenge;
    
    return $challenge;
}

/**
 * Add anti-robot field to comment form
 */
function mochin_add_robot_check_field($fields) {
    $challenge = mochin_generate_robot_check();
    $error = isset($_SESSION['mochin_robot_error']) ? $_SESSION['mochin_robot_error'] : '';
    
    // Clear error after displaying
    if ($error) {
        unset($_SESSION['mochin_robot_error']);
    }
    
    $error_html = '';
    if (!empty($error)) {
        $error_html = '<span style="color: #dc2626; display: block; margin-top: 0.5rem; font-size: 0.875rem;">' . esc_html($error) . '</span>';
    }
    
    $fields['anti_robot_check'] = sprintf(
        '<p class="comment-form-anti-robot-check">' .
        '<label for="anti_robot_check">%s <span class="required">*</span><br><strong>%s</strong></label>' .
        '%s' .
        '<input id="anti_robot_check" name="anti_robot_check" type="text" value="" size="30" maxlength="50" autocomplete="off" required style="width: 100%%; max-width: 300px; padding: 0.75rem; border: 1px solid #e5e7eb; border-radius: 0.375rem; margin-top: 0.5rem;" />' .
        '</p>',
        __('Anti-Robot Check', 'mochin-theme'),
        $challenge,
        $error_html
    );
    
    return $fields;
}
add_filter('comment_form_default_fields', 'mochin_add_robot_check_field');

/**
 * Validate anti-robot check on comment submission
 */
function mochin_validate_robot_check($commentdata) {
    if (!is_user_logged_in()) {
        $submitted = isset($_POST['anti_robot_check']) ? trim($_POST['anti_robot_check']) : '';
        $expected = isset($_SESSION['mochin_robot_check']) ? $_SESSION['mochin_robot_check'] : '';
        $post_id = isset($_POST['comment_post_ID']) ? intval($_POST['comment_post_ID']) : 0;
        
        if (empty($submitted) || $submitted !== $expected) {
            // Store error in session
            $_SESSION['mochin_robot_error'] = 'Anti-Robot Check failed. Please enter the correct value shown above.';
            
            // Force session save
            session_write_close();
            session_start();
            
            // Redirect back to post with error
            wp_safe_redirect(get_permalink($post_id) . '#respond');
            exit;
        }
        
        // Clear the challenge and any errors after successful validation
        unset($_SESSION['mochin_robot_check']);
        unset($_SESSION['mochin_robot_error']);
    }
    
    return $commentdata;
}
add_filter('preprocess_comment', 'mochin_validate_robot_check');

/**
 * Remove cookies consent checkbox from comment form
 */
function mochin_remove_cookies_consent($fields) {
    if (isset($fields['cookies'])) {
        unset($fields['cookies']);
    }
    return $fields;
}
add_filter('comment_form_default_fields', 'mochin_remove_cookies_consent', 20);

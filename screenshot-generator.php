<?php
/**
 * Dynamic Screenshot Generator for Social Media Sharing
 * Generates Open Graph images on-the-fly
 */

// Load WordPress
require_once('../../../wp-load.php');

// Get post ID from query string
$post_id = isset($_GET['post_id']) ? intval($_GET['post_id']) : 0;

if (!$post_id || get_post_status($post_id) !== 'publish') {
    header('HTTP/1.0 404 Not Found');
    exit('Post not found');
}

// Get post data
$post = get_post($post_id);
$title = get_the_title($post_id);
$site_name = get_bloginfo('name');
$domain = parse_url(home_url(), PHP_URL_HOST);

// Image dimensions (optimal for social media)
$width = 1200;
$height = 630;

// Create image
$image = imagecreatetruecolor($width, $height);

// Colors
$white = imagecolorallocate($image, 255, 255, 255);
$black = imagecolorallocate($image, 17, 24, 39);
$gray = imagecolorallocate($image, 107, 114, 128);
$lightGray = imagecolorallocate($image, 229, 231, 235);

// Fill background
imagefill($image, 0, 0, $white);

// Add padding
$padding = 60;
$contentWidth = $width - ($padding * 2);

// Draw site name (top)
$fontSize = 18;
$fontFile = __DIR__ . '/assets/fonts/arial.ttf';

// Use system font if custom font not available
if (!file_exists($fontFile)) {
    // Draw site name with built-in font
    imagestring($image, 5, $padding, $padding, $site_name, $gray);
    
    // Draw title - wrap text
    $titleY = $padding + 80;
    $titleLines = mochin_wrap_text($title, 30);
    $lineHeight = 80;
    
    foreach ($titleLines as $index => $line) {
        if ($index >= 3) break; // Max 3 lines for bigger text
        imagestring($image, 5, $padding, $titleY + ($index * $lineHeight), $line, $black);
    }
    
    // Draw footer line
    $footerY = $height - $padding - 60;
    imageline($image, $padding, $footerY, $width - $padding, $footerY, $black);
    imagesetthickness($image, 3);
    imageline($image, $padding, $footerY, $width - $padding, $footerY, $black);
    
    // Draw domain
    imagestring($image, 4, $padding, $footerY + 20, $domain, $gray);
    
    // Draw logo box
    $logoSize = 60;
    imagefilledrectangle(
        $image,
        $width - $padding - $logoSize,
        $footerY + 10,
        $width - $padding,
        $footerY + 10 + $logoSize,
        $black
    );
} else {
    // Use TrueType fonts if available
    imagettftext($image, 18, 0, $padding, $padding + 30, $gray, $fontFile, $site_name);
    
    // Title with word wrapping - BIGGER SIZE
    $titleLines = mochin_wrap_text($title, 25);
    $titleY = $padding + 120;
    $lineHeight = 85;
    
    foreach ($titleLines as $index => $line) {
        if ($index >= 3) break; // Max 3 lines for bigger text
        imagettftext($image, 64, 0, $padding, $titleY + ($index * $lineHeight), $black, $fontFile, $line);
    }
    
    // Footer
    $footerY = $height - $padding - 60;
    imageline($image, $padding, $footerY, $width - $padding, $footerY, $black);
    imagesetthickness($image, 3);
    imageline($image, $padding, $footerY, $width - $padding, $footerY, $black);
    
    imagettftext($image, 20, 0, $padding, $footerY + 45, $gray, $fontFile, $domain);
    
    $logoSize = 60;
    imagefilledrectangle(
        $image,
        $width - $padding - $logoSize,
        $footerY + 10,
        $width - $padding,
        $footerY + 10 + $logoSize,
        $black
    );
}

// Output image
header('Content-Type: image/png');
header('Cache-Control: public, max-age=86400'); // Cache for 24 hours
imagepng($image, null, 9);
imagedestroy($image);

/**
 * Wrap text to fit width
 */
function mochin_wrap_text($text, $maxChars) {
    $words = explode(' ', $text);
    $lines = array();
    $currentLine = '';
    
    foreach ($words as $word) {
        if (strlen($currentLine . ' ' . $word) <= $maxChars) {
            $currentLine .= ($currentLine ? ' ' : '') . $word;
        } else {
            if ($currentLine) {
                $lines[] = $currentLine;
            }
            $currentLine = $word;
        }
    }
    
    if ($currentLine) {
        $lines[] = $currentLine;
    }
    
    return $lines;
}

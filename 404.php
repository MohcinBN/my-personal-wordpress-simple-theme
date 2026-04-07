<?php
/**
 * The template for displaying 404 pages
 */

get_header();
?>

<div class="error-404 text-center py-16">
    <h1 class="text-6xl font-bold text-gray-300 dark:text-gray-600 mb-4">404</h1>
    <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">Page Not Found</h2>
    <p class="text-gray-600 dark:text-gray-400 mb-8">
        The page you're looking for doesn't exist or has been moved.
    </p>
    <a href="<?php echo esc_url(home_url('/')); ?>" class="inline-block bg-gray-900 dark:bg-white text-white dark:text-gray-900 px-6 py-3 rounded-lg font-medium hover:bg-gray-700 dark:hover:bg-gray-200 transition-colors no-underline">
        Back to Home
    </a>
</div>

<?php get_footer(); ?>

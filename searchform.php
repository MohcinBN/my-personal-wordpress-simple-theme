<?php
/**
 * Search form template
 */
?>

<form role="search" method="get" class="search-form" action="<?php echo esc_url(home_url('/')); ?>">
    <label class="screen-reader-text" for="search-field"><?php _e('Search for:', 'mochin-theme'); ?></label>
    <div class="flex gap-2">
        <input type="search" id="search-field" class="flex-grow px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-gray-400" placeholder="<?php esc_attr_e('Search...', 'mochin-theme'); ?>" value="<?php echo get_search_query(); ?>" name="s">
        <button type="submit" class="px-4 py-2 bg-gray-900 dark:bg-white text-white dark:text-gray-900 rounded-lg font-medium hover:bg-gray-700 dark:hover:bg-gray-200 transition-colors">
            <?php _e('Search', 'mochin-theme'); ?>
        </button>
    </div>
</form>

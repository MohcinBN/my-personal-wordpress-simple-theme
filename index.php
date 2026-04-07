<?php
/**
 * The main template file
 */

get_header();
?>

<?php if (have_posts()) : ?>
    
    <div class="posts-list space-y-12">
        <?php while (have_posts()) : the_post(); ?>
            
            <article id="post-<?php the_ID(); ?>" <?php post_class('post-item'); ?>>
                <header class="post-header mb-4">
                    <h2 class="text-xl font-bold mb-2">
                        <a href="<?php the_permalink(); ?>" class="text-gray-900 dark:text-white hover:text-gray-600 dark:hover:text-gray-300 no-underline">
                            <?php the_title(); ?>
                        </a>
                    </h2>
                </header>

                <div class="post-excerpt text-gray-600 dark:text-gray-400 mb-4 leading-relaxed">
                    <?php if (has_excerpt()) : ?>
                        <?php the_excerpt(); ?>
                    <?php else : ?>
                        <?php echo wp_trim_words(get_the_content(), 30); ?>
                    <?php endif; ?>
                </div>

                <footer class="post-meta flex items-center justify-between text-sm">
                    <a href="<?php the_permalink(); ?>" class="text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white underline">
                        Read More
                    </a>
                    <time datetime="<?php echo get_the_date('c'); ?>" class="text-gray-400 dark:text-gray-500">
                        <?php echo get_the_date('F j, Y'); ?>
                    </time>
                </footer>
            </article>

        <?php endwhile; ?>
    </div>

    <?php if (get_next_posts_link() || get_previous_posts_link()) : ?>
        <nav class="pagination mt-12 pt-8 border-t border-gray-200 dark:border-gray-700">
            <div class="flex justify-between">
                <div class="prev">
                    <?php previous_posts_link('&larr; New Articles'); ?>
                </div>
                <div class="next">
                    <?php next_posts_link('Old Articles &rarr;'); ?>
                </div>
            </div>
        </nav>
    <?php endif; ?>

<?php else : ?>
    
    <div class="no-posts text-center py-12">
        <h2 class="text-xl font-bold mb-4">No posts found</h2>
        <p class="text-gray-600 dark:text-gray-400">There are no posts to display.</p>
    </div>

<?php endif; ?>

<?php get_footer(); ?>

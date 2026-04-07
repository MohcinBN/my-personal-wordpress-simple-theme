<?php
/**
 * The template for displaying single posts
 */

get_header();
?>

<?php while (have_posts()) : the_post(); ?>

    <article id="post-<?php the_ID(); ?>" <?php post_class('single-post'); ?>>
        <header class="post-header mb-8">
            <h1 class="text-3xl font-bold mb-4 text-gray-900 dark:text-white">
                <?php the_title(); ?>
            </h1>
            <div class="post-meta text-gray-500 dark:text-gray-400 text-sm">
                <time datetime="<?php echo get_the_date('c'); ?>">
                    <?php echo get_the_date('F j, Y'); ?>
                </time>
            </div>
        </header>

        <div class="post-content prose dark:prose-invert max-w-none">
            <?php the_content(); ?>
        </div>

        <?php if (has_tag()) : ?>
            <footer class="post-footer mt-8 pt-8 border-t border-gray-200 dark:border-gray-700">
                <div class="post-tags">
                    <span class="text-gray-500 dark:text-gray-400 text-sm">Tags: </span>
                    <?php the_tags('<span class="text-sm">', ', ', '</span>'); ?>
                </div>
            </footer>
        <?php endif; ?>
    </article>

    <nav class="post-navigation mt-12 pt-8 border-t border-gray-200 dark:border-gray-700">
        <div class="flex justify-between">
            <div class="prev-post">
                <?php
                $prev_post = get_previous_post();
                if ($prev_post) :
                ?>
                    <a href="<?php echo get_permalink($prev_post); ?>" class="text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white no-underline">
                        &larr; <?php echo esc_html($prev_post->post_title); ?>
                    </a>
                <?php endif; ?>
            </div>
            <div class="next-post">
                <?php
                $next_post = get_next_post();
                if ($next_post) :
                ?>
                    <a href="<?php echo get_permalink($next_post); ?>" class="text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white no-underline">
                        <?php echo esc_html($next_post->post_title); ?> &rarr;
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <?php if (comments_open() || get_comments_number()) : ?>
        <section class="comments-section mt-12 pt-8 border-t border-gray-200 dark:border-gray-700">
            <?php comments_template(); ?>
        </section>
    <?php endif; ?>

<?php endwhile; ?>

<?php get_footer(); ?>

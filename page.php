<?php
/**
 * The template for displaying pages
 */

get_header();
?>

<?php while (have_posts()) : the_post(); ?>

    <article id="page-<?php the_ID(); ?>" <?php post_class('single-page'); ?>>
        <header class="page-header mb-8">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">
                <?php the_title(); ?>
            </h1>
        </header>

        <div class="page-content prose dark:prose-invert max-w-none">
            <?php the_content(); ?>
        </div>
    </article>

    <?php if (comments_open() || get_comments_number()) : ?>
        <section class="comments-section mt-12 pt-8 border-t border-gray-200 dark:border-gray-700">
            <?php comments_template(); ?>
        </section>
    <?php endif; ?>

<?php endwhile; ?>

<?php get_footer(); ?>

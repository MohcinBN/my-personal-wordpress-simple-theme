<?php
/**
 * The template for displaying comments
 */

if (post_password_required()) {
    return;
}
?>

<div id="comments" class="comments-area">

    <?php if (have_comments()) : ?>
        <h2 class="comments-title text-xl font-bold mb-6">
            <?php
            $comment_count = get_comments_number();
            printf(
                _n('%s Comment', '%s Comments', $comment_count, 'mochin-theme'),
                number_format_i18n($comment_count)
            );
            ?>
        </h2>

        <ol class="comment-list">
            <?php
            wp_list_comments(array(
                'style'       => 'ol',
                'short_ping'  => true,
                'avatar_size' => 48,
            ));
            ?>
        </ol>

        <?php
        the_comments_navigation(array(
            'prev_text' => '&larr; Older Comments',
            'next_text' => 'Newer Comments &rarr;',
        ));
        ?>

    <?php endif; ?>

    <?php if (!comments_open() && get_comments_number() && post_type_supports(get_post_type(), 'comments')) : ?>
        <p class="no-comments text-gray-500 dark:text-gray-400">Comments are closed.</p>
    <?php endif; ?>

    <?php
    comment_form(array(
        'title_reply'          => '<span class="text-xl font-bold">Leave a Comment</span>',
        'title_reply_before'   => '<h3 id="reply-title" class="comment-reply-title mb-4">',
        'title_reply_after'    => '</h3>',
        'class_form'           => 'comment-form mt-6',
        'class_submit'         => 'submit-button',
        'comment_notes_before' => '',
        'comment_notes_after'  => '',
    ));
    ?>

</div>

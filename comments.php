<?php
/* If a post password is required or no comments are given and comments/pings are closed, return. */
if ( post_password_required() || ( !have_comments() && !comments_open() && !pings_open() ) )
        return;

// get user's comment display setting
$comments_display = get_theme_mod('ct_ignite_comments_setting');

/*
 * Get the current post type
 * Have to maintain options set as "posts, pages, attachments" or break users settings.
 * Adding the 's' to make the post type match the Customizer setting.
 */
$post_type = get_post_type() . 's';

// error prevention
if( is_array( $comments_display ) ) {

    // if the current post type is not included, don't display comments
    if( ! in_array( $post_type, $comments_display ) ) {
        return;
    }
}


if ( comments_open() ) { ?>

    <section id="comments" class="comments">
        <div class="comments-number">
            <h3>
                <?php comments_number( __( 'Be First to Comment', 'ignite' ), __( 'One Comment', 'ignite'), __( '% Comments', 'ignite' ) ); ?>
            </h3>
        </div>
        <ol class="comment-list">
                <?php wp_list_comments(array( 'callback'=>'ct_ignite_customize_comments', 'max_depth'=>'7') ); ?>
        </ol><!-- .comment-list -->
        <?php
        if( (get_option('page_comments') == 1) && (get_comment_pages_count() > 1) ) { ?>
            <nav class="comment-pagination">
                <p class="previous-comment"><?php previous_comments_link(); ?></p><p class="next-comment"><?php next_comments_link(); ?></p>
            </nav>
        <?php } ?>
        <?php comment_form(); ?>
    </section><!-- #comments -->
<?php
} elseif(!comments_open() && have_comments() && pings_open() ) { ?>
    <section id="comments" class="comments">
        <div class="comments-number">
            <h3>
                <?php comments_number( __( 'Be First to Comment', 'ignite' ), __( 'One Comment', 'ignite'), __( '% Comments', 'ignite' ) ); ?>
            </h3>
        </div>
        <ol class="comment-list">
                <?php wp_list_comments(array( 'callback'=>'ct_ignite_customize_comments', 'max_depth'=>'3') ); ?>
        </ol><!-- .comment-list -->
        <?php
        if( (get_option('page_comments') == 1) && (get_comment_pages_count() > 1) ) { ?>
            <nav class="comment-pagination">
                <p class="previous-comment"><?php previous_comments_link(); ?></p><p class="next-comment"><?php next_comments_link(); ?></p>
            </nav>
        <?php } ?>
        <p class="comments-closed pings-open">
                <?php printf( __( 'Comments are closed, but <a href="%s" title="Trackback URL for this post">trackbacks</a> and pingbacks are open.', 'ignite' ), esc_url( get_trackback_url() ) ); ?>
        </p><!-- .comments-closed .pings-open -->
    </section><!-- #comments -->
<?php
} elseif ( !comments_open() && have_comments() ) { ?>
    <section id="comments" class="comments">
        <div class="comments-number">
            <h3>
                <?php comments_number( __( 'Be First to Comment', 'ignite' ), __( 'One Comment', 'ignite'), __( '% Comments', 'ignite' ) ); ?>
            </h3>
        </div>
        <ol class="comment-list">
                <?php wp_list_comments(array( 'callback'=>'ct_ignite_customize_comments', 'max_depth'=>'3') ); ?>
        </ol><!-- .comment-list -->
        <?php
        if( (get_option('page_comments') == 1) && (get_comment_pages_count() > 1) ) { ?>
            <nav class="comment-pagination">
                <p class="previous-comment"><?php previous_comments_link(); ?></p><p class="next-comment"><?php next_comments_link(); ?></p>
            </nav>
        <?php } ?>
        <p class="comments-closed">
            <?php _e( 'Comments are closed.', 'ignite' ); ?>
        </p><!-- .comments-closed -->
    </section><!-- #comments -->
<?php 
} else { ?>

    <p class="comments-closed">
            <?php _e( 'Comments are closed.', 'ignite' ); ?>
    </p><!-- .comments-closed -->

<?php } ?>

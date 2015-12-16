<?php
/* If a post password is required or no comments are given and comments/pings are closed, return. */
if ( post_password_required() || ( ! have_comments() && ! comments_open() && ! pings_open() ) ) {
	return;
}

// Get post type & add an "s" to match setting in Customizer
$post_type        = get_post_type() . 's';
$comments_display = get_theme_mod( 'ct_ignite_comments_setting' );

if ( is_array( $comments_display ) ) {
	if ( ! in_array( $post_type, $comments_display ) ) {
		return;
	}
}

if ( comments_open() ) { ?>

	<section id="comments" class="comments">
		<div class="comments-number">
			<h3>
				<?php comments_number( __( 'Be First to Comment', 'ignite' ), __( 'One Comment', 'ignite' ), __( '% Comments', 'ignite' ) ); ?>
			</h3>
		</div>
		<ol class="comment-list">
			<?php wp_list_comments( array( 'callback' => 'ct_ignite_customize_comments', 'max_depth' => '7' ) ); ?>
		</ol>
		<?php
		if ( ( get_option( 'page_comments' ) == 1 ) && ( get_comment_pages_count() > 1 ) ) { ?>
			<nav class="comment-pagination">
				<p class="previous-comment"><?php previous_comments_link(); ?></p>

				<p class="next-comment"><?php next_comments_link(); ?></p>
			</nav>
		<?php } ?>
		<?php comment_form(); ?>
	</section>
	<?php
} elseif ( ! comments_open() && have_comments() && pings_open() ) { ?>
	<section id="comments" class="comments">
		<div class="comments-number">
			<h3>
				<?php comments_number( __( 'Be First to Comment', 'ignite' ), __( 'One Comment', 'ignite' ), __( '% Comments', 'ignite' ) ); ?>
			</h3>
		</div>
		<ol class="comment-list">
			<?php wp_list_comments( array( 'callback' => 'ct_ignite_customize_comments', 'max_depth' => '3' ) ); ?>
		</ol>
		<?php
		if ( ( get_option( 'page_comments' ) == 1 ) && ( get_comment_pages_count() > 1 ) ) { ?>
			<nav class="comment-pagination">
				<p class="previous-comment"><?php previous_comments_link(); ?></p>

				<p class="next-comment"><?php next_comments_link(); ?></p>
			</nav>
		<?php } ?>
		<p class="comments-closed pings-open">
			<?php printf( __( 'Comments are closed, but <a href="%s" title="Trackback URL for this post">trackbacks</a> and pingbacks are open.', 'ignite' ), esc_url( get_trackback_url() ) ); ?>
		</p>
	</section>
	<?php
} elseif ( ! comments_open() && have_comments() ) { ?>
	<section id="comments" class="comments">
		<div class="comments-number">
			<h3>
				<?php comments_number( __( 'Be First to Comment', 'ignite' ), __( 'One Comment', 'ignite' ), __( '% Comments', 'ignite' ) ); ?>
			</h3>
		</div>
		<ol class="comment-list">
			<?php wp_list_comments( array( 'callback' => 'ct_ignite_customize_comments', 'max_depth' => '3' ) ); ?>
		</ol>
		<?php
		if ( ( get_option( 'page_comments' ) == 1 ) && ( get_comment_pages_count() > 1 ) ) { ?>
			<nav class="comment-pagination">
				<p class="previous-comment"><?php previous_comments_link(); ?></p>

				<p class="next-comment"><?php next_comments_link(); ?></p>
			</nav>
		<?php } ?>
		<p class="comments-closed">
			<?php _e( 'Comments are closed.', 'ignite' ); ?>
		</p>
	</section>
	<?php
} else { ?>
	<p class="comments-closed">
		<?php _e( 'Comments are closed.', 'ignite' ); ?>
	</p>
<?php } ?>
